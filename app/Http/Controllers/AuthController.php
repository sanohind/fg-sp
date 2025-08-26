<?php

namespace App\Http\Controllers;

use App\Models\UsersModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);

        // Fetch user with role name
        $user = DB::table('users')
            ->join('roles', 'roles.id', '=', 'users.role_id')
            ->select('users.*', 'roles.role_name as role')
            ->where('users.username', $validated['username'])
            ->first();

        $passwordOk = false;
        if ($user) {
            // Support both hashed and plain-text (if dataset is not hashed yet)
            $passwordOk = Hash::check($validated['password'], (string)($user->password ?? ''))
                || ((string)($user->password ?? '') === $validated['password']);
        }

        if (!$user || !$passwordOk) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'Username atau password salah.'])
                ->with('error', 'Username atau password salah. Silakan coba lagi.');
        }

        // Minimal session-based login (avoids guard setup)
        $request->session()->put('user', [
            'id' => $user->id ?? null,
            'username' => $user->username ?? null,
            'name' => $user->name ?? null,
            'role' => $user->role ?? null,
        ]);
        $request->session()->regenerate();

        $role = strtolower((string)($user->role ?? ''));
        if ($role === 'admin') {
            return redirect()->route('admin.home')->with('success', 'Selamat datang, ' . ($user->name ?? 'Admin') . '!');
        }
        if ($role === 'operator') {
            return redirect()->route('operator.index')->with('success', 'Selamat datang, ' . ($user->name ?? 'Operator') . '!');
        }

        // Default fallback
        return redirect()->route('admin.home')->with('success', 'Selamat datang, ' . ($user->name ?? 'User') . '!');
    }

    public function logout(Request $request)
    {
        $userName = session('user.name') ?? 'User';
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout. Sampai jumpa, ' . $userName . '!');
    }
}


