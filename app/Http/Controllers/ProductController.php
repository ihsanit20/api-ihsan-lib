<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Milon\Barcode\DNS1D;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories:id,name', 'authors:id,name,photo'])->get();
        return response()->json($products);
    }

    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $categoryId = $request->get('category_id', null);

        $products = Product::with(['categories:id,name', 'authors:id,name,photo'])
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('ISBN', 'like', "%$query%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->whereHas('categories', fn($query) => $query->where('id', $categoryId));
            })
            ->get();

        return response()->json($products);
    }

    public function find(Request $request)
    {
        $id = $request->get('id');
        $barcode = $request->get('barcode');

        $product = Product::with(['categories:id,name', 'authors:id,name,photo'])
            ->when($id, fn($query) => $query->where('id', $id))
            ->when($barcode, fn($query) => $query->where('barcode', $barcode))
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'ISBN' => 'nullable|string',
            'description' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'authors' => 'nullable|array',
            'authors.*.id' => 'exists:authors,id',
            'authors.*.role' => 'required|in:author,translator',
        ]);

        $product = Product::create($request->only(
            'name',
            'ISBN',
            'description',
            'mrp',
            'selling_price'
        ));

        if ($request->has('categories')) {
            $product->categories()->attach($request->categories);
        }
        if ($request->has('authors')) {
            $authors = collect($request->authors)->mapWithKeys(function ($author) {
                return [$author['id'] => ['role' => $author['role']]];
            });
            $product->authors()->attach($authors);
        }

        return response()->json($product->load(['categories', 'authors']), 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $barcodeGenerator = new DNS1D();
        
        $barcodeImage = 'data:image/png;base64,' . $barcodeGenerator->getBarcodePNG($product->barcode, 'C39', 2, 60);

        return response()->json([
            'product' => $product,
            'barcodeImage' => $barcodeImage,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'ISBN' => 'nullable|string',
            'description' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'authors' => 'nullable|array',
            'authors.*.id' => 'exists:authors,id',
            'authors.*.role' => 'required|in:author,translator',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only(
            'name',
            'ISBN',
            'description',
            'mrp',
            'selling_price'
        ));

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        if ($request->has('authors')) {
            $authors = collect($request->authors)->mapWithKeys(function ($author) {
                return [$author['id'] => ['role' => $author['role']]];
            });
            $product->authors()->sync($authors);
        }

        return response()->json($product->load(['categories', 'authors']));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->categories()->detach();
        $product->authors()->detach();
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    public function uploadPhoto(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $request->validate([
            'photo' => 'required|image|max:6144',
        ]);

        $image = Image::read($request->file('photo'));
        $image->cover(300, 400);

        $path = 'product/photos/' . $product->id . '.webp';

        Storage::disk('s3')->put($path, $image->toWebp(100));

        $s3Url = Storage::disk('s3')->url($path);

        $product->photo = $s3Url;
        $product->save();

        return response()->json(['message' => 'Photo uploaded successfully', 'photo' => $s3Url], 200);
    }
}
