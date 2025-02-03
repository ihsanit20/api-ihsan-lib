<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryCharge;

class DeliveryChargeController extends Controller
{
    public function show()
    {
        $charge = DeliveryCharge::first();
        return response()->json($charge);
    }

    public function update(Request $request)
    {
        $request->validate([
            'inside_city' => 'required|numeric|min:0',
            'outside_city' => 'required|numeric|min:0',
        ]);

        $charge = DeliveryCharge::first();
        $charge->update([
            'inside_city' => $request->inside_city,
            'outside_city' => $request->outside_city,
        ]);

        return response()->json(['message' => 'Delivery charges updated successfully', 'data' => $charge]);
    }
}
