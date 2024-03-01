<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\Absensi;
use App\Models\PengajuanPKL;
use App\Models\User; // Tambahkan use statement untuk model User
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DataAbsenController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan ID pembimbing yang sedang login
        $pembimbingId = Auth::id();

        // Mengambil data permohonan PKL yang terkait dengan pembimbing yang sedang login
        $pengajuanPkl = PengajuanPKL::where('pembimbing_id_1', $pembimbingId)
            ->orWhere('pembimbing_id_2', $pembimbingId)
            ->get();

        // Kumpulkan id user siswa dari pengajuan PKL yang dibimbing oleh pembimbing
        $siswaIds = $pengajuanPkl->pluck('user_id')->toArray();

        // Ambil data siswa berdasarkan id yang terkumpul, hanya ambil id, nisn, name, dan email
        $siswa = User::whereIn('id', $siswaIds)->get(['id', 'nisn', 'name', 'email']);

        // Jika tidak ada siswa yang terhubung dengan pembimbing, kembalikan respons kosong
        if ($siswa->isEmpty()) {
            return response()->json(['message' => 'Anda belum dihubungkan dengan siswa untuk PKL'], 404);
        }

        // Mengambil semua data absensi yang terkait dengan siswa yang dibimbing oleh pembimbing tersebut
        $absensi = Absensi::whereIn('user_id', $siswaIds)->get();

        // Mengembalikan data absensi dalam bentuk respons JSON
        return response()->json(['absensi' => $absensi]);
    }
}
