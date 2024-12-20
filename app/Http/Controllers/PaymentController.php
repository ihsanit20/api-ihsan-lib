<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:Cash,Card,Mobile Banking,Other',
            'remarks' => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($request->amount > $order->remaining_due) {
            return response()->json(['error' => 'Payment exceeds the remaining due amount'], 400);
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'remarks' => $request->remarks,
            'payment_date' => now(),
        ]);

        $order->total_paid += $request->amount;
        $order->remaining_due -= $request->amount;
        if ($order->remaining_due == 0) {
            $order->status = 'Completed';
        }
        $order->save();

        return response()->json(['message' => 'Payment added successfully', 'payment' => $payment]);
    }

    public function index(Request $request)
    {
        $payments = Payment::with('order.user')
            ->when($request->order_id, fn($query, $orderId) => $query->where('order_id', $orderId))
            ->orderBy('payment_date', 'desc')
            ->get();

        return response()->json($payments);
    }

    public function show($id)
    {
        $payment = Payment::with('order.user')->findOrFail($id);
        return response()->json($payment);
    }
}