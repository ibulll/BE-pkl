<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Absensi;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsenSiswaController extends Controller
{
    public function index()
    {
        // Ambil daftar siswa yang memiliki akses ke absensi
        $siswaList = User::where('role_id', 4)->get(['id', 'name']);

        // Ambil NISN dari tabel PengajuanPKL
        $nisnData = PengajuanPKL::whereIn('user_id', $siswaList->pluck('id'))->pluck('nisn', 'user_id');
        
        // Gabungkan NISN dengan data siswa
        $siswaList->transform(function ($user) use ($nisnData) {
            $user->nisn = $nisnData[$user->id] ?? null;
            return $user;
        });

        return response()->json($siswaList);
    }

    public function show($id)
    {
        // Ambil data absensi berdasarkan user_id
        $absensiList = Absensi::where('user_id', $id)->get();
    
        // Jika data absensi tidak ditemukan, kembalikan respons kesalahan
        if ($absensiList->isEmpty()) {
            return response()->json(['message' => 'Data absensi tidak ditemukan untuk siswa dengan ID ' . $id], 404);
        }
    
        // Mengambil data siswa terkait dari data absensi pertama yang ditemukan
        $absensiPertama = $absensiList->first();
        $siswaId = $absensiPertama->user_id;
    
        // Mengambil data siswa terkait berdasarkan ID siswa yang ditemukan
        $siswa = User::find($siswaId);
    
        // Mendapatkan data foto dan lokasi absensi
        $absensiData = $absensiList->map(function ($absensi) {
            return [
                'tanggal_absen' => $absensi->created_at->toDateString(),
                'latitude' => $absensi->latitude,
                'longitude' => $absensi->longitude,
                'foto' => trim(Storage::url($absensi->foto), '\\'), // Menghapus backslash dari URL
            ];
        });
    
        // Kembalikan data absensi beserta data siswa jika diperlukan
        return response()->json(['siswa' => $siswa, 'absensiList' => $absensiData]);
    }
}
