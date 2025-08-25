<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string'],
        ]);

        $user = User::where('username', $validated['username'])->first();
        if (!$user || !Hash::check($validated['password'], (string)($user->password ?? ''))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken($validated['device_name'] ?? 'api')->plainTextToken;

        $role = DB::table('roles')->where('id', $user->role_id)->value('role_name');

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'role' => $role,
            ],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        $role = DB::table('roles')->where('id', $user->role_id)->value('role_name');
        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'role' => $role,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}


