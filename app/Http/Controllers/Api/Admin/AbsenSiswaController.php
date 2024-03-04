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
    try {
        // Ambil daftar siswa yang memiliki akses ke absensi beserta NISN dan kelas dari tabel users
        $siswaList = User::where('role_id', 4)->get(['id', 'name', 'nisn', 'kelas']);

        return response()->json($siswaList);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error fetching siswa data.' . $e->getMessage()], 500);
    }
}


    public function show($id)
    {
        // Ambil semua data absensi berdasarkan user_id
        $absensiList = Absensi::where('user_id', $id)->get();

        // Jika tidak ada data absensi ditemukan, kembalikan respons kesalahan
        if ($absensiList->isEmpty()) {
            return response()->json(['message' => 'Data absensi tidak ditemukan untuk siswa dengan ID ' . $id], 404);
        }

        // Mengambil data siswa terkait dari absensi pertama
        $siswa = $absensiList->first()->user;

        // Menginisialisasi array untuk menyimpan data absensi
        $formattedAbsensiList = [];

        // Mengiterasi melalui setiap data absensi dan menyimpannya dalam array yang diformat
        foreach ($absensiList as $absensi) {
            $absensiData = [
                'tanggal_absen' => $absensi->created_at->toDateString(),
                'waktu_absen' => $absensi->waktu_absen, // Mengubah menjadi toTimeString()
                'latitude' => $absensi->latitude,
                'longitude' => $absensi->longitude,
                'foto' => trim(Storage::url($absensi->foto), '\\'), // Menghapus backslash dari URL
            ];

            $formattedAbsensiList[] = $absensiData;
        }

        // Kembalikan data absensi beserta data siswa
        return response()->json(['siswa' => $siswa, 'absensiList' => $formattedAbsensiList]);
    }


}