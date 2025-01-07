<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::all();
        return response()->json($galleries);
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:6144',
        ]);

        $image = Image::read($request->file('photo'));
        $image->cover(1280, 320);

        $path = $this->getS3Prefix($request) . '/gallery/' . uniqid() . '.webp';

        Storage::disk('s3')->put($path, $image->toWebp(70));

        $s3Url = Storage::disk('s3')->url($path);

        $gallery = new Gallery();
        $gallery->photo = $s3Url;
        $gallery->save();

        return response()->json(['message' => 'Photo uploaded successfully', 'path' => $s3Url], 200);
    }

    public function updateLink(Request $request, $id)
    {
        $request->validate([
            'link' => 'required|url|max:255',
        ]);
        $gallery = Gallery::findOrFail($id);

        $gallery->link = $request->input('link');
        $gallery->save();

        return response()->json([
            'message' => 'Link updated successfully',
            'gallery' => $gallery,
        ], 200);
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);

        Storage::disk('s3')->delete(parse_url($gallery->photo));

        $gallery->delete();

        return response()->json(null, 204);
    }
}