<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Models\Absensi;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
        ]);

        // Ambil pengajuan PKL terkait (berdasarkan pengguna yang sedang login)
        $pengajuanPkl = PengajuanPKL::where('user_id', Auth::id())->first();

        // Pastikan pengajuan PKL ditemukan
        if (!$pengajuanPkl) {
            return response()->json(['message' => 'Data pengajuan PKL tidak ditemukan'], 404);
        }

        // Periksa apakah status pengajuan PKL sudah diterima
        if ($pengajuanPkl->status !== 'Diterima') {
            return response()->json(['message' => 'Anda belum dapat melakukan absensi karena permohonan PKL Anda belum diterima'], 403);
        }

        // Dapatkan tanggal absen dari permintaan
        $tanggal_absen = Carbon::today()->toDateString(); // Tanggal absen default adalah tanggal hari ini

        // Ambil waktu saat ini
        $now = Carbon::now();

        // Ambil waktu 7 pagi hari ini
        $resetTime = Carbon::now()->setTime(7, 0, 0);

        // Periksa apakah sudah melewati waktu reset
        if ($now->greaterThan($resetTime)) {
            // Reset absensi untuk hari ini
            Absensi::where('user_id', Auth::id())->whereDate('tanggal_absen', $now->toDateString())->delete();
        }

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
        $absensi->waktu_absen = $now->toTimeString(); // Waktu absen diambil dari waktu sekarang
        $absensi->longitude = $request->longitude;
        $absensi->foto = $fotoPath; // Simpan path foto ke database
        $absensi->user_id = Auth::id();
        $absensi->save();

        // Mendapatkan URL untuk foto yang disimpan
        $fotoUrl = Storage::url($fotoPath);

        // Respons berhasil absen beserta URL foto
        return response()->json([
            'message' => 'Absensi berhasil disimpan',
            'tanggal_absen' => $tanggal_absen,
            'waktu_absen' => $now->toTimeString(),
            'foto_url' => $fotoUrl
        ], 201);
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
<<<<<<< HEAD
=======

}
>>>>>>> 91ad7c35ace3cd3546b1c61f540972adff77d58f

}