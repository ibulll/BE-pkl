<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function getDaftarKelas()
    {
        try {
            $daftarKelas = [
                'XII PPLG 1',
                'XII PPLG 2',
                'XII PPLG 3',
            ];

            return response()->json($daftarKelas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching daftar kelas: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
{
    // Validasi request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users,email',
        'role' => 'required|string|in:admin,kaprog,pembimbing,siswa',
        'nisn' => ($request->input('role') === 'siswa') ? 'string|unique:users,nisn' : '',
        'nip' => ($request->input('role') === 'pembimbing' || $request->input('role') === 'kaprog') ? 'string|unique:users,nip' : '',
        'nomer_telpon' => ($request->input('role') === 'pembimbing' || $request->input('role') === 'kaprog') ? 'string' : '',
        'kelas' => ($request->input('role') === 'siswa') ? 'required|in:XII PPLG 1,XII PPLG 2,XII PPLG 3' : '', // Validasi kelas hanya untuk siswa
        'jabatan' => ($request->input('role') === 'pembimbing') ? 'required|string' : '', // Menambahkan validasi jabatan
        'pangkat' => ($request->input('role') === 'pembimbing') ? 'nullable|string' : '', // Menambahkan validasi pangkat
    ]);

    if ($validator->fails()) {
        // Mengambil pesan kesalahan dari validator
        $errors = $validator->errors()->all();
        return response()->json(['error' => 'Validasi gagal', 'message' => $errors], 400);
    }

    try {
        // Cari role_id berdasarkan nama peran
        $role = Role::where('name', $request->input('role'))->firstOrFail();

        // Buat user baru
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role_id = $role->id;
        // Set data berdasarkan peran pengguna
        if ($request->input('role') === 'siswa') {
            $user->nisn = $request->input('nisn');
            $user->kelas = $request->input('kelas');
        } elseif ($request->input('role') === 'pembimbing' || $request->input('role') === 'kaprog') {
            $user->nip = $request->input('nip');
            $user->nomer_telpon = $request->input('nomer_telpon');
            // Tambahkan kolom jabatan dan pangkat
            $user->jabatan = $request->input('jabatan');
            $user->pangkat = $request->input('pangkat');
        }
        $user->password = bcrypt($request->input('password'));

        $user->assignRole($request->input('role'));

        $user->save();

        return response()->json(['message' => 'User berhasil dibuat'], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Gagal membuat user', 'message' => $e->getMessage()], 500);
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

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            // Validasi data yang diperbolehkan untuk diupdate sesuai peran pengguna
            $allowedFields = ['name', 'email'];
            if ($user->hasRole(['pembimbing', 'kaprog'])) {
                $allowedFields = array_merge($allowedFields, ['nip', 'nomer_telpon', 'password']);
            } elseif ($user->hasRole('siswa')) {
                $allowedFields = array_merge($allowedFields, ['nisn', 'password']);
            }

            // Filter data yang diizinkan untuk diupdate
            $data = $request->only($allowedFields);

            // Update user data
            $user->update($data);

            // Optionally, update the password if provided
            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->input('password'))]);
            }

            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating user: ' . $e->getMessage()], 500);
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