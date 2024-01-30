<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Models\User;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PengajuanPKLController extends Controller
{
    // ...

    public function searchSiswa(Request $request)
    {
        try {
            $nama = $request->input('nama');
    
            // Lakukan pencarian nama siswa berdasarkan role_id
            $siswa = User::where('role_id', 4)
                ->where('name', 'like', '%' . $nama . '%')
                ->get(['id', 'name']);
    
            // Cek apakah data ditemukan
            if ($siswa->isEmpty()) {
                return response()->json(['error' => 'Siswa tidak ditemukan'], 404);
            }
    
            return response()->json($siswa);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error searching for students'], 500);
        }
    }
}    