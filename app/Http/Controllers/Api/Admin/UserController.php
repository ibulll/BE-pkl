<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $searchTerm = request('searchTerm');
            $roleCategory = request('roleCategory');



            $query = User::with('role');

            // Apply search by name
            if ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            }

            // Apply filtering by role category
            if ($roleCategory) {
                $query->whereHas('role', function ($q) use ($roleCategory) {
                    $q->where('name', $roleCategory);
                });
            }

            $users = $query->get();

            return response()->json(['users' => $users], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Error fetching users'], 500);
        }
    }

    public function getUsersByRoleId(Request $request)
    {
        try {
            $role_id = $request->input('role_id');

            if (!$role_id) {
                return response()->json(['message' => 'Role ID is required'], 400);
            }

            $users = User::with('role')->where('role_id', $role_id)->get();

            return response()->json(['users' => $users], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Error fetching users by role ID'], 500);
        }
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
            $validatedData = $request->validated();
            $user = User::findOrFail($id);

            // Update user data
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            // Optionally, update the password if provided
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->input('password'))]);
            }

            // Update user roles
            $roleIds = [$validatedData['role_id']]; // Role baru yang diinput dari form

            // Hapus peran lama dan tambahkan peran baru
            $user->syncRoles($roleIds);

            $user->role_id = $validatedData['role_id'];
            $user->save();

            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating user'], 500);
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