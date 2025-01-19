<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->with([
                'publisher:id,name',
                'categories:id,name',
                'authors:id,name,photo',
            ])
            ->latest()
            ->get();

        return ProductResource::collection($products);
    }

    public function filter(Request $request)
    {
        $query = Product::query();

        if ($request->has('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id); // Explicit table name added
            });
        }

        if ($request->has('author_id')) {
            $query->whereHas('authors', function ($q) use ($request) {
                $q->where('authors.id', $request->author_id); // Explicit table name added
            });
        }

        $products = $query->with(['categories:id,name', 'authors:id,name,photo'])->latest()->get();
        return ProductResource::collection($products);
    }

    public function randomProducts()
    {
        $products = Product::with(['categories:id,name', 'authors:id,name,photo'])
            ->inRandomOrder()
            ->take(10)
            ->get();

        return ProductResource::collection($products);
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

        return ProductResource::collection($products);
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

        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
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
            'publisher_id',
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
        $product = Product::with(['categories', 'authors'])->findOrFail($id);
        return new ProductResource($product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
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
            'publisher_id',
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

        $path = $this->getS3Prefix($request) . '/products/' . $product->id . '.webp';

        Storage::disk('s3')->put($path, $image->toWebp(100));

        $s3Url = Storage::disk('s3')->url($path);

        $product->photo = $s3Url;
        $product->save();

        return response()->json(['message' => 'Photo uploaded successfully', 'photo' => $s3Url], 200);
    }
}
