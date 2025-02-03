<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\District;
use App\Models\Area;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function divisions()
    {
        return response()->json(Division::all());
    }

    public function districts(Request $request)
    {
        $query = District::query();
        if ($request->has('division_id')) {
            $query->where('division_id', $request->division_id);
        }
        return response()->json($query->get());
    }

    public function areas(Request $request)
    {
        $query = Area::query();
        if ($request->has('district_id')) {
            $query->where('district_id', $request->district_id);
        }
        return response()->json($query->get());
    }
}
