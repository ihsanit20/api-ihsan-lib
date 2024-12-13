<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::with('product:id,name,photo')->get();
        return response()->json($stocks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'production_price' => 'required|numeric|min:0',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_date' => 'nullable|date',
        ]);

        $stock = Stock::create($request->all());
        return response()->json($stock, 201);
    }

    public function show($id)
    {
        $stock = Stock::with('product')->findOrFail($id);
        return response()->json($stock);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'production_price' => 'required|numeric|min:0',
            'mrp' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_date' => 'nullable|date',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->update($request->all());
        return response()->json($stock);
    }

    public function destroy($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return response()->json(['message' => 'Stock deleted successfully']);
    }
}
