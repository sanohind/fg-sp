<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:superadmin');
    }

    public function index()
    {
        $users = User::with('role')->get();
        
        // Calculate statistics
        $totalUsers = $users->count();
        $superAdminCount = 0;
        $adminCount = 0;
        $operatorCount = 0;
        
        // Count users by role
        foreach ($users as $user) {
            if ($user->role) {
                switch(strtolower($user->role->role_name)) {
                    case 'superadmin':
                        $superAdminCount++;
                        break;
                    case 'admin':
                        $adminCount++;
                        break;
                    case 'operator':
                        $operatorCount++;
                        break;
                }
            }
        }
        
        return view('admin.user', compact('users', 'totalUsers', 'superAdminCount', 'adminCount', 'operatorCount'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.add-user', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah ada dalam sistem.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'name.required' => 'Nama harus diisi.',
            'role_id.required' => 'Role harus dipilih.',
            'role_id.exists' => 'Role tidak ditemukan.',
        ]);

        try {
            User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan user. Silakan coba lagi.');
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.edit-user', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'name' => 'required|string|max:255',
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah ada dalam sistem.',
            'name.required' => 'Nama harus diisi.',
            'role_id.required' => 'Role harus dipilih.',
            'role_id.exists' => 'Role tidak ditemukan.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            $data = [
                'username' => $request->username,
                'name' => $request->name,
                'role_id' => $request->role_id,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.user.index')->with('success', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui user. Silakan coba lagi.');
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Prevent self-deletion
            if ($user->id == auth()->id()) {
                return redirect()->route('admin.user.index')->with('error', 'Anda tidak dapat menghapus akun sendiri.');
            }

            $user->delete();

            return redirect()->route('admin.user.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.user.index')->with('error', 'Gagal menghapus user. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $user = User::with('role')->findOrFail($id);
        return view('admin.show-user', compact('user'));
    }
}
