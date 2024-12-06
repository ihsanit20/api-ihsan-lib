<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => '',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'photo' => 'nullable|string',
            'role' => 'required|string|in:developer,admin,instructor,student',
        ]);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => '',
            'password' => 'sometimes|string|min:8|confirmed',
            'phone' => 'sometimes|string|max:20',
            'photo' => 'nullable|string',
            'role' => 'required|string|in:developer,admin,instructor,student',
        ]);

        if($request->role == 'developer' && auth('sanctum')?->user()?->role != 'developer') {
            return abort(404);
        }

        $user->update($validated);

        return response()->json($user);
    }

    public function getUsers()
    {
        $users = User::query()
            ->when(auth('sanctum')?->user()?->role != 'developer', function ($query) {
                $query->whereNot('role', 'developer');
            })
            ->get();
            
        return response()->json($users);
    }
}
