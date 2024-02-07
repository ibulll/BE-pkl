<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Models\Absensi;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function store(Request $request)
{
    // Validasi data absensi
    $request->validate([
        'latitude' => 'required',
        'longitude' => 'required',
        'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'tanggal_absen' => 'required|date' // Validasi tanggal absen
    ]);

    // Simpan foto absensi
    $fotoPath = $request->file('foto')->store('absensi');

    // Ambil pengajuan PKL terkait (berdasarkan pengguna yang sedang login)
    $pengajuanPkl = PengajuanPKL::where('user_id', Auth::id())->first();
    
    // Pastikan pengajuan PKL ditemukan
    if (!$pengajuanPkl) {
        return response()->json(['message' => 'Data pengajuan PKL tidak ditemukan'], 404);
    }

    // Ambil NISN dan nama dari pengajuan PKL
    $nisn = $pengajuanPkl->nisn;
    $nama = $pengajuanPkl->nama;
    
    // Dapatkan tanggal absen dari permintaan
    $tanggal_absen = $request->tanggal_absen;

    // Simpan data absensi ke database, termasuk NISN, nama, latitude, longitude, dan foto
    $absensi = new Absensi();
    $absensi->nama = $nama;
    $absensi->nisn = $nisn;
    $absensi->tanggal_absen = $tanggal_absen; // Simpan tanggal absen
    $absensi->latitude = $request->latitude;
    $absensi->longitude = $request->longitude;
    $absensi->foto = $fotoPath;
    $absensi->user_id = Auth::id();
    $absensi->save();

    // Respons berhasil absen
    return response()->json(['message' => 'Absensi berhasil disimpan'], 201);
}

}