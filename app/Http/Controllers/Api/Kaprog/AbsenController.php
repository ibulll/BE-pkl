<?php

namespace App\Http\Controllers\Api\Kaprog;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User; // Menggunakan model User

class AbsenController extends Controller
{
    public function index()
    {
        // Mengambil semua data siswa dengan role_id 4
        $siswa = User::where('role_id', 4)->get(['id', 'name', 'nisn', 'kelas']);

        // Mengembalikan data siswa dalam bentuk response JSON
        return response()->json(['siswa' => $siswa]);
    }

    public function show($id)
    {
        // Mengambil data absensi berdasarkan ID siswa
        $absensi = Absensi::where('user_id', $id)->get();

        // Mengembalikan data absensi dalam bentuk response JSON
        return response()->json(['absensi' => $absensi]);
    }
}
