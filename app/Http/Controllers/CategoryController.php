<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $category = Category::create($request->only('name', 'description'));

        if ($request->has('products')) {
            $category->products()->attach($request->products);
        }

        return response()->json($category->load('products'), 201);
    }

    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->only('name', 'description'));

        if ($request->has('products')) {
            $category->products()->sync($request->products);
        }

        return response()->json($category->load('products'));
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->products()->detach();
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
