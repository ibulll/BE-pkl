<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Models\Absensi;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function store(Request $request)
{
    // Validasi data absensi
    $request->validate([
        'latitude' => 'nullable',
        'longitude' => 'nullable',
        'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'waktu_absen' => 'required',
        'tanggal_absen' => 'required|date' // Validasi tanggal absen
    ]);

    // Ambil pengajuan PKL terkait (berdasarkan pengguna yang sedang login)
    $pengajuanPkl = PengajuanPKL::where('user_id', Auth::id())->first();
    
    // Pastikan pengajuan PKL ditemukan
    if (!$pengajuanPkl) {
        return response()->json(['message' => 'Data pengajuan PKL tidak ditemukan'], 404);
    }

    // Dapatkan tanggal absen dari permintaan
    $tanggal_absen = $request->tanggal_absen;

    // Periksa apakah siswa sudah melakukan absensi pada tanggal yang sama
    $absensiSiswa = Absensi::where('user_id', Auth::id())
                           ->whereDate('tanggal_absen', $tanggal_absen)
                           ->exists();

    if ($absensiSiswa) {
        return response()->json(['message' => 'Anda sudah Absen Hari Ini'], 400);
    }

    // Simpan foto absensi ke penyimpanan yang telah ditentukan sebelumnya
    $fotoPath = $request->file('foto')->store('photos', 'public');

    // Simpan data absensi ke database, termasuk NISN, nama, latitude, longitude, dan foto
    $absensi = new Absensi();
    $absensi->tanggal_absen = $tanggal_absen; // Simpan tanggal absen
    $absensi->latitude = $request->latitude;
    $absensi->waktu_absen = $request->waktu_absen;
    $absensi->longitude = $request->longitude;
    $absensi->foto = $fotoPath; // Simpan path foto ke database
    $absensi->user_id = Auth::id();
    $absensi->save();

    // Mendapatkan URL untuk foto yang disimpan
    $fotoUrl = Storage::url($fotoPath);

    // Respons berhasil absen beserta URL foto
    return response()->json(['message' => 'Absensi berhasil disimpan', 'foto_url' => $fotoUrl], 201); 
}

    public function list()
{
    // Ambil daftar absensi yang telah disimpan
    $absensiList = Absensi::where('user_id', Auth::id())->get();

    // Mendapatkan URL untuk setiap foto absensi
    $absensiListWithUrl = $absensiList->map(function ($absensi) {
        $absensi->foto_url = Storage::url($absensi->foto);
        return $absensi;
    });

    return response()->json($absensiListWithUrl);
}

}

