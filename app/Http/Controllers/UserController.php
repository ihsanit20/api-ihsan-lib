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
        'password' => 'nullable|string|confirmed',
        'address' => 'nullable|string',
        'role' => 'nullable|string|in:customer,staff,admin,developer',
        'customer_type' => 'nullable|string|in:regular,retailer,wholesale,distributor',
    ], [
        // কাস্টম মেসেজ
        'name.required' => 'নাম প্রদান করা আবশ্যক।',
        'name.max' => 'নামের সর্বোচ্চ দৈর্ঘ্য ২৫৫ অক্ষর হতে পারে।',
        'phone.required' => 'ফোন নম্বর প্রদান করা আবশ্যক।',
        'phone.unique' => 'ইতোমধ্যে ক্রেতা তৈরী করা আছে।',
        'phone.max' => 'ফোন নম্বর ২০ অক্ষরের বেশি হতে পারবে না।',
        'password.confirmed' => 'পাসওয়ার্ড নিশ্চিতকরণ মেলেনি।',
        'role.in' => 'নির্বাচিত ভূমিকা বৈধ নয়।',
        'customer_type.in' => 'গ্রাহকের ধরন বৈধ নয়।',
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
            'password' => 'sometimes|string|confirmed',
            'address' => 'nullable|string',
            'role' => 'nullable|string|in:customer,staff,admin,developer', // Removed 'required'
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

    public function searchUser(Request $request)
    {
        $id = $request->query('id');
        $phone = $request->query('phone');

        if (!$id && !$phone) {
            return response()->json([
                'status' => 'error',
                'message' => 'Provide either ID or Phone number.',
            ], 400);
        }

        $query = User::query();

        if ($id) {
            $query->where('id', $id);
        }

        if ($phone) {
            $query->where('phone', $phone);
        }

        $user = $query->first();

        if ($user) {
            return response()->json([
                'status' => 'found',
                'user' => $user,
            ]);
        }

        return response()->json([
            'status' => 'not_found',
            'message' => 'User not found. You can create a new user.',
        ]);
    }
}
