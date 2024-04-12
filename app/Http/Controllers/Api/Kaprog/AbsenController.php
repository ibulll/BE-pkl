<?php

namespace App\Http\Controllers\Api\Kaprog;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
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
        $absensi = Absensi::where('user_id', $id)->get(['tanggal_absen', 'waktu_absen', 'latitude', 'longitude', 'foto']);
    
        // Menghapus backslash dari URL foto
        foreach ($absensi as $absen) {
            $absen->foto = trim(Storage::url($absen->foto), '\\');
        }
    
        // Mengembalikan data absensi dalam bentuk response JSON
        return response()->json(['absensi' => $absensi]);
    }
    
    
    
}
