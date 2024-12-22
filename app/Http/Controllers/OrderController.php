<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with(['user:id,name,phone', 'orderDetails.product', 'payments'])
            ->when($request->type, fn($query, $type) => $query->where('type', $type))
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'type' => 'nullable|in:online,offline',
            'payment.amount' => 'nullable|numeric|min:0',
            'payment.method' => 'nullable|in:Cash,Card,Mobile Banking,Other',
            'payment.remarks' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $totalPrice = array_sum(array_map(fn($item) => $item['quantity'] * $item['price'], $request->products));

        $discount = $request->discount ?? 0;
        $payableAmount = $totalPrice - $discount;

        $totalPaid = $request->payment['amount'] ?? 0;

        $order = Order::create([
            'user_id' => $request->user_id,
            'total_price' => $totalPrice,
            'discount' => $discount,
            'payable_amount' => $payableAmount,
            'total_paid' => $totalPaid,
            'remaining_due' => $payableAmount - $totalPaid,
            'type' => $request->type ?? 'offline',
            'status' => $payableAmount === $totalPaid ? 'Completed' : 'Pending',
        ]);

        foreach ($request->products as $product) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
            ]);
        }

        if (!empty($request->payment['amount'])) {
            Payment::create([
                'order_id' => $order->id,
                'user_id' => $request->user_id,
                'amount' => $totalPaid,
                'payment_method' => $request->payment['method'] ?? 'Cash',
                'remarks' => $request->payment['remarks'] ?? null,
                'payment_date' => now(),
            ]);
        }

        return response()->json($order->load('orderDetails.product', 'payments'), 201);
    }


    public function show($id)
    {
        $order = Order::with('orderDetails.product', 'payments')->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Completed,Cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json(['message' => 'Order status updated successfully', 'order' => $order]);
    }

    public function cancel($id)
    {
        $order = Order::with('orderDetails')->findOrFail($id);

        if ($order->status !== 'Pending') {
            return response()->json(['error' => 'Only pending orders can be cancelled'], 400);
        }

        foreach ($order->orderDetails as $detail) {
            $stock = $detail->product->stocks()->orderBy('stock_date', 'desc')->first();
            if ($stock) {
                $stock->quantity += $detail->quantity;
                $stock->save();
            }
        }

        $order->update(['status' => 'Cancelled']);

        return response()->json(['message' => 'Order cancelled and stock returned successfully']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->orderDetails()->delete();
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }

    public function report(Request $request)
    {
        $orders = Order::with('orderDetails.product', 'payments')
            ->when($request->type, fn($query, $type) => $query->where('type', $type))
            ->when($request->date_from, fn($query, $date) => $query->where('order_date', '>=', $date))
            ->when($request->date_to, fn($query, $date) => $query->where('order_date', '<=', $date))
            ->orderBy('order_date', 'desc')
            ->get();

        return response()->json($orders);
    }
}
