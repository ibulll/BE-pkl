<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\User;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardPembimbingController extends Controller
{
    public function siswaDibimbing(Request $request)
    {
        try {
            // Dapatkan id pembimbing dari pembimbing yang sedang login
            $pembimbingId = auth()->user()->id;

            // Ambil data pengajuan PKL yang memiliki pembimbing_id_1 atau pembimbing_id_2 yang sesuai
            $pengajuanPkl = PengajuanPKL::where('pembimbing_id_1', $pembimbingId)
                ->orWhere('pembimbing_id_2', $pembimbingId)
                ->get();

            // Kumpulkan id user siswa dari pengajuan PKL yang dibimbing oleh pembimbing
            $siswaIds = $pengajuanPkl->pluck('user_id')->toArray();

            // Ambil data siswa berdasarkan id yang terkumpul, hanya ambil id, nisn, name, dan email
            $siswa = User::whereIn('id', $siswaIds)->get(['id', 'nisn', 'name', 'email']);

            // Periksa apakah ada siswa yang dibimbing oleh pembimbing
            if ($siswa->isEmpty()) {
                return response()->json(['message' => 'Tidak ada siswa yang dibimbing oleh pembimbing ini.'], 404);
            }

            return response()->json($siswa);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data siswa.', 'message' => $e->getMessage()], 500);
        }
    }

}
