<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\User;
use App\Models\Absensi;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DataAbsenController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan ID pembimbing yang sedang login
        $pembimbingId = Auth::id();

        // Mengambil data pengajuan PKL yang memiliki pembimbing_id yang sesuai
        $pengajuanPkl = PengajuanPKL::where('pembimbing_id_1', $pembimbingId)
            ->orWhere('pembimbing_id_2', $pembimbingId)
            ->get();

        // Kumpulkan id user siswa dari pengajuan PKL yang dibimbing oleh pembimbing
        $siswaIds = $pengajuanPkl->pluck('user_id')->toArray();

        // Ambil data siswa berdasarkan id yang terkumpul, hanya ambil id, nisn, name, kelas, dan email
        $siswa = User::whereIn('id', $siswaIds)->get(['id', 'nisn', 'name', 'kelas', 'email']);

        // Jika tidak ada siswa yang terhubung dengan pembimbing, kembalikan respons kosong
        if ($siswa->isEmpty()) {
            return response()->json(['message' => 'Anda belum dihubungkan dengan siswa untuk PKL'], 404);
        }

        // Mengembalikan data siswa dalam bentuk respons JSON
        return response()->json(['siswa' => $siswa]);
    }

    public function show($id)
    {
        // Ambil data absensi berdasarkan user_id
        $absensi = Absensi::where('user_id', $id)->get();
    
        // Jika tidak ada data absensi ditemukan, kembalikan respons kesalahan
        if ($absensi->isEmpty()) {
            return response()->json(['message' => 'Data absensi tidak ditemukan untuk siswa dengan ID ' . $id], 404);
        }
    
        // Menginisialisasi array untuk menyimpan data absensi
        $formattedAbsensiList = [];
    
        // Mengiterasi melalui setiap data absensi dan menyimpannya dalam array yang diformat
        foreach ($absensi as $absen) {
            $absenData = [
                'tanggal_absen' => $absen->tanggal_absen,
                'waktu_absen' => $absen->waktu_absen,
                'latitude' => $absen->latitude,
                'longitude' => $absen->longitude,
                'foto' => trim(Storage::url($absen->foto), '\\'), // Menghapus backslash dari URL
            ];
    
            $formattedAbsensiList[] = $absenData;
        }
    
        // Kembalikan data absensi dalam bentuk respons JSON
        return response()->json(['absensi' => $formattedAbsensiList]);
    }
}
