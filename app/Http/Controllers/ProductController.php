<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'authors'])->get();
        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'ISBN' => 'nullable|string',
            'description' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'authors' => 'nullable|array',
            'authors.*.id' => 'exists:authors,id',
            'authors.*.role' => 'required|in:author,translator',
        ]);

        $product = Product::create($request->only('name', 'ISBN', 'description'));

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
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'ISBN' => 'nullable|string',
            'description' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'authors' => 'nullable|array',
            'authors.*.id' => 'exists:authors,id',
            'authors.*.role' => 'required|in:author,translator',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only('name', 'ISBN', 'description'));

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