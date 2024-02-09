<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AbsenSiswaController extends Controller
{
    public function index()
    {
        // Ambil 'nama' dan 'nisn' dari tabel 'absensi'
        $siswaList = Absensi::select('nisn', 'nama')->get();

        return response()->json($siswaList);
    }

    public function show($id)
{
    // Menggunakan model Absensi untuk mengambil data absensi berdasarkan nama atau ID
    $absensiList = Absensi::where('nama', $id)->orWhere('id', $id)->get();

    // Jika data absensi tidak ditemukan, kembalikan respons kesalahan
    if ($absensiList->isEmpty()) {
        return response()->json(['message' => 'Data absensi tidak ditemukan untuk siswa dengan nama atau ID ' . $id], 404);
    }

    // Mendapatkan data siswa terkait dari data absensi pertama yang ditemukan
    $absensiPertama = $absensiList->first();
    $siswaId = $absensiPertama->id;

    // Mengambil data siswa terkait berdasarkan ID siswa yang ditemukan
    $siswa = Absensi::where('id', $siswaId)->first();

    // Mendapatkan data foto dan lokasi absensi
    $absensiData = $absensiList->map(function ($absensi) {
        return [
            'latitude' => $absensi->latitude,
            'longitude' => $absensi->longitude,
            'foto' => trim(Storage::url($absensi->foto), '\\'), // Menghapus backslash dari URL
        ];
    });  
          

    // Kembalikan data absensi beserta data siswa jika diperlukan
    return response()->json(['absensiList' => $absensiData]);
}

}
