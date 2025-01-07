<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class AuthorController extends Controller
{
    public function index()
    {
        return response()->json(Author::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $author = Author::create($validated);

        return response()->json($author, 201);
    }

    public function show($id)
    {
        $author = Author::findOrFail($id);
        return response()->json($author);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $author = Author::findOrFail($id);
        $author->update($validated);

        return response()->json($author);
    }

    public function destroy($id)
    {
        $author = Author::findOrFail($id);
        $author->delete();

        return response()->json(['message' => 'Author deleted successfully']);
    }

    public function uploadPhoto(Request $request, $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $request->validate([
            'photo' => 'required|image|max:6144',
        ]);

        $image = Image::read($request->file('photo'));

        $image->cover(200, 200);

        $path = $this->getS3Prefix($request) . '/authors/' . $author->id . '.webp';

        Storage::disk('s3')->put($path, $image->toWebp(100));

        $s3Url = Storage::disk('s3')->url($path);

        $author->photo = $s3Url;

        $author->save();

        return response()->json(['message' => 'Photo uploaded successfully', 'photo' => $s3Url], 200);
    }
}
