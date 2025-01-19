<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class PublisherController extends Controller
{
    public function index()
    {
        $publishers = Publisher::all();
        return response()->json($publishers, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:publishers,name',
            'description' => 'nullable',
            'is_own' => 'required|boolean',
        ]);

        $publisher = Publisher::create($request->only(['name', 'description', 'is_own']));

        return response()->json(['message' => 'Publisher created successfully', 'publisher' => $publisher], 201);
    }

    public function show($id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404);
        }

        return response()->json($publisher, 200);
    }

    public function update(Request $request, $id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404);
        }

        $request->validate([
            'name' => 'required|unique:publishers,name,' . $id,
            'description' => 'nullable',
            'is_own' => 'required|boolean',
        ]);

        $publisher->update($request->only(['name', 'description', 'is_own']));

        return response()->json(['message' => 'Publisher updated successfully', 'publisher' => $publisher], 200);
    }

    public function destroy($id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404);
        }

        if ($publisher->photo) {
            Storage::disk('s3')->delete($this->getS3Prefix(request()) . '/' . $publisher->photo);
        }

        $publisher->delete();

        return response()->json(['message' => 'Publisher deleted successfully']);
    }

    public function uploadPhoto(Request $request, $id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404);
        }

        $request->validate([
            'photo' => 'required|image|max:6144', // Max size: 6 MB
        ]);

        $image = Image::make($request->file('photo'));
        $image->fit(400, 400);

        $path = $this->getS3Prefix($request) . '/publishers/' . $publisher->id . '.webp';

        Storage::disk('s3')->put($path, (string) $image->encode('webp', 90));

        $s3Url = Storage::disk('s3')->url($path);

        $publisher->photo = $s3Url;
        $publisher->save();

        return response()->json(['message' => 'Photo uploaded successfully', 'photo' => $s3Url], 200);
    }
}
