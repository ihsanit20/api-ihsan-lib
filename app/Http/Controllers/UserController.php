<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'nullable|string|min:8|confirmed',
            'address' => 'nullable|string',
            'role' => 'required|string|in:customer,staff,admin,developer',
            'customer_type' => 'nullable|string|in:regular,retailer,wholesale,distributor',
        ]);

        $validated['role'] = $validated['role'] ?? 'customer';

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20|unique:users,phone,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'address' => 'nullable|string',
            'role' => 'sometimes|string|in:customer,staff,admin,developer',
            'customer_type' => 'nullable|string|in:regular,retailer,wholesale,distributor',
        ]);

        if ($request->has('role') && $request->role === 'developer' && auth('sanctum')?->user()?->role !== 'developer') {
            return abort(403, 'Unauthorized role update.');
        }

        $user->update($validated);

        return response()->json($user);
    }

    public function getUsers()
    {
        $users = User::query()
            ->when(auth('sanctum')?->user()?->role !== 'developer', function ($query) {
                $query->whereNot('role', 'developer');
            })
            ->get();

        return response()->json($users);
    }
}
