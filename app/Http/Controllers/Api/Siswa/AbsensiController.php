<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Models\Absensi;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbsensiController extends Controller
{
    public function store(Request $request)
    {
        // Validasi data absensi
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Simpan foto absensi
        $fotoPath = $request->file('foto')->store('absensi');

        // Ambil NISN dan nama dari pengajuan PKL terkait (misalnya berdasarkan user yang sedang login)
        $pengajuanPkl = PengajuanPKL::where('user_id', auth()->id())->first();
        $nisn = $pengajuanPkl->nisn;
        $nama = $pengajuanPkl->nama;

        // Simpan data absensi ke database, termasuk NISN, nama, latitude, longitude, dan foto
        $absensi = new Absensi();
        $absensi->nama = $nama;
        $absensi->nisn = $nisn;
        $absensi->latitude = $request->latitude;
        $absensi->longitude = $request->longitude;
        $absensi->foto = $fotoPath;
        $absensi->save();

        // Respons berhasil absen
        return response()->json(['message' => 'Absensi berhasil disimpan'], 201);
    }
}
