<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();

        return response()->json([
            'users' => $users,
        ], 200);
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|string|in:admin,kaprog,pembimbing,siswa',
            ]);
    
            // Menetapkan peran
            $role = Role::where('name', $request->input('role'))->first();
    
            if (!$role) {
                return response()->json([
                    'message' => 'Peran yang diberikan tidak valid.',
                ], 422);
            }
    
            // Membuat User
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role_id' => $role->id,
            ]);
    
            // Menetapkan peran menggunakan Spatie Laravel Permission (sesuaikan dengan package yang Anda gunakan)
            $user->assignRole($request->input('role'));
    
            // Menyegarkan data pengguna untuk mendapatkan data yang diperbarui, termasuk peran
            $user = $user->fresh();
    
            if (!$user->role) {
                // Handle jika peran tidak terhubung dengan pengguna
                return response()->json([
                    'message' => 'Gagal menetapkan peran untuk pengguna.',
                ], 500);
            }
    
            return response()->json([
                'user' => $user,
                'message' => 'Pengguna berhasil dibuat.',
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => 'Terjadi kesalahan. Silakan coba lagi nanti.',
            ], 500);
        }
    }
    


    public function show($id)
    {
        // User Detail 
        $users = User::find($id);
        if (!$users) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }

        // Return Json Response
        return response()->json([
            'users' => $users
        ], 200);
    }

    public function update(UserStoreRequest $request, $id)
{
    try {
        // Find User
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }

        // Update User
        $user->name = $request->name;
        $user->email = $request->email;

        // Find or create role based on the input role name
        $role = Role::firstOrCreate(['name' => $request->role]);

        // Set the role_id for the user
        $user->role_id = $role->id;

        // Save the changes
        $user->save();

        // Return Json Response
        return response()->json([
            'message' => "User successfully updated."
        ], 200);
    } catch (\Exception $e) {
        // Return Json Response with detailed error message
        return response()->json([
            'message' => "Something went really wrong! Error: " . $e->getMessage(),
        ], 500);
    }
}

    

    public function destroy($id)
    {
        // Detail 
        $users = User::find($id);
        if (!$users) {
            return response()->json([
                'message' => 'User Not Found.'
            ], 404);
        }

        // Delete User
        $users->delete();

        // Return Json Response
        return response()->json([
            'message' => "User successfully deleted."
        ], 200);

    }
}