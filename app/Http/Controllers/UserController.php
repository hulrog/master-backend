<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all();
        if ($users->isEmpty()) {
            return response()->json(['message' => 'No users found'], 404);
        }
        return response()->json(['message' => 'Users retrieved successfully', 'users' => $users], 200);
    }

    public function createUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'email|max:255',
            'password' => 'string',
            'date_of_birth' => 'date',
            'gender' => 'string|',
            'country_id' => 'integer|exists:countries,country_id',
        ]);
        $validatedData['date_joined'] = now();
        $user = User::create($validatedData);
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|max:255',
            'gender' => 'string',
            'country_id' => 'integer|exists:countries,country_id',
        ]);
        $user->update($validatedData);
        return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    public function getUsersByName($name)
    {
        $users = User::where('name', 'like', "%$name%")->get();
        if ($users->isEmpty()) {
            return response()->json(['message' => 'Users with that name not found'], 404);
        }
        return response()->json(['message' => 'Users with that name retrieved successfully', 'users' => $users], 200);
    }
}
