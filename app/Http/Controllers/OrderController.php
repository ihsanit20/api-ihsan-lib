<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::query()
            ->with([
                'user:id,name,phone',
                'orderDetails.product',
                'payments',
            ])
            ->when($request->type, fn($query, $type) => $query->where('type', $type))
            ->when($request->status, fn($query, $status) => $query->where('status', $status))
            ->when($request->date, fn($query, $date) => $query->whereDate('created_at', $date))
            ->when($request->from, fn($query, $from) => $query->whereDate('created_at', '>=', $from))
            ->when($request->to, fn($query, $to) => $query->whereDate('created_at', '<=', $to))
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
            'discount_amount' => 'nullable|numeric|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'adjust_amount' => 'nullable|numeric',
        ]);

        $totalPrice = array_sum(array_map(fn($item) => $item['quantity'] * $item['price'], $request->products));

        $discountPercentage = $request->discount_percentage ?? 0;
        $discountAmount = ($discountPercentage > 0) ? ($totalPrice * $discountPercentage / 100) : ($request->discount_amount ?? 0);

        $adjustAmount = $request->adjust_amount ?? 0;

        $payableAmount = $totalPrice - $discountAmount - $adjustAmount; // Adjust reduces payable amount

        $paidAmount = $request->payment['amount'] ?? 0;

        $dueAmount = $payableAmount - $paidAmount;
        $status = ($dueAmount > 0) ? 'Pending' : 'Completed';

        $order = Order::create([
            'user_id' => $request->user_id,
            'total_price' => $totalPrice,
            'discount_percentage' => $discountPercentage,
            'discount_amount' => $discountAmount,
            'adjust_amount' => $adjustAmount,
            'payable_amount' => $payableAmount,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'type' => $request->type ?? 'offline',
            'status' => $status,
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
                'amount' => $paidAmount,
                'payment_method' => $request->payment['method'] ?? 'Cash',
                'remarks' => $request->payment['remarks'] ?? null,
                'payment_date' => now(),
            ]);
        }

        return response()->json($order->load('orderDetails.product', 'payments'), 201);
    }

    public function show($id)
    {
        $order = Order::with('user:id,name,phone,address', 'orderDetails.product', 'payments')->findOrFail($id);
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

    public function onlineOrder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'delivery_charge' => 'required|numeric|min:0',
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'alt_phone' => 'nullable|string',
            'addressData' => 'required|array',
            'addressData.area_id' => 'required|exists:areas,id',
        ]);

        $totalPrice = array_sum(array_map(function ($item) {
            return $item['quantity'] * $item['price'];
        }, $request->products));

        $deliveryCharge = $request->delivery_charge;
        $payableAmount = $totalPrice + $deliveryCharge;

        $shippingDetails = [
            'name'      => $request->name,
            'phone'     => $request->phone,
            'alt_phone' => $request->alt_phone,
            'address'   => $request->address,
            'area_id'   => $request->addressData['area_id'],
        ];

        $order = Order::create([
            'user_id'             => $request->user_id,
            'total_price'         => $totalPrice,
            'delivery_charge'     => $deliveryCharge,
            'discount_percentage' => 0,
            'discount_amount'     => 0,
            'adjust_amount'       => 0,
            'payable_amount'      => $payableAmount,
            'paid_amount'         => 0,
            'due_amount'          => $payableAmount,
            'type'                => 'online',
            'status'              => 'Pending',
            'shipping_details'    => $shippingDetails,
        ]);

        foreach ($request->products as $product) {
            OrderDetail::create([
                'order_id'   => $order->id,
                'product_id' => $product['product_id'],
                'quantity'   => $product['quantity'],
                'price'      => $product['price'],
            ]);
        }

        return response()->json($order->load('orderDetails.product'), 201);
    }

    public function myOrders(Request $request)
    {
        $user = $request->user();

        $orders = Order::with(['orderDetails.product', 'payments'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function myOrder(Request $request, $id)
    {
        $user = $request->user();

        $order = Order::with(['orderDetails.product', 'payments'])
            ->where('user_id', $user->id)
            ->findOrFail($id);

        $shipping_details = $order->shipping_details;
        if (isset($shipping_details['area_id'])) {
            $area = Area::with('district')->find($shipping_details['area_id']);
            if ($area) {
                $shipping_details['area_name'] = $area->name;
                $shipping_details['district_name'] = $area->district ? $area->district->name : null;
            }
            $order->shipping_details = $shipping_details;
        }

        return response()->json($order);
    }
}