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

        // Ambil NISN dan kelas dari tabel PengajuanPKL
        $nisnData = PengajuanPKL::whereIn('user_id', $siswaList->pluck('id'))->pluck('nisn', 'user_id');
        $kelasData = PengajuanPKL::whereIn('user_id', $siswaList->pluck('id'))->pluck('kelas', 'user_id');

        // Gabungkan NISN dengan data siswa
        $siswaList->transform(function ($user) use ($nisnData, $kelasData) {
            $user->nisn = $nisnData[$user->id] ?? null;
            $user->kelas = $kelasData[$user->id] ?? null;
            return $user;
        });

        return response()->json($siswaList);
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
                'waktu_absen' => $absensi->created_at->toTimeString(), // Mengubah menjadi toTimeString()
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