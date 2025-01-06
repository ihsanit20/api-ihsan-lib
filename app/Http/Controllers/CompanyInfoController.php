<?php

namespace App\Http\Controllers;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class CompanyInfoController extends Controller
{
    public function index()
    {
        $companyInfo = CompanyInfo::first();
        return response()->json($companyInfo, 200);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'alt_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'fb_link' => 'nullable|url',
            'yt_link' => 'nullable|url',
            'x_link' => 'nullable|url',
            'in_link' => 'nullable|url',
        ]);

        $companyInfo = CompanyInfo::first();
        if (!$companyInfo) {
            return response()->json(['message' => 'Company info not found'], 404);
        }

        $companyInfo->update($validatedData);

        return response()->json([
            'message' => 'Company information updated successfully',
            'data' => $companyInfo
        ], 200);
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|max:2048',
        ]);

        $image = Image::read($request->file('photo'));
        $image->cover(200, 100);

        $logoPath = $request->file('logo')->store('logos', 's3');
        $url = Storage::disk('s3')->url($logoPath, $image->toWebp(100));

        $companyInfo = CompanyInfo::first();
        if (!$companyInfo) {
            return response()->json(['message' => 'Company info not found'], 404);
        }

        $companyInfo->update(['logo' => $url]);

        return response()->json([
            'message' => 'Logo uploaded successfully',
            'url' => $url
        ], 200);
    }
}
