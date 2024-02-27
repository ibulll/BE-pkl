<?php

namespace App\Http\Controllers\Api\Siswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPKL;
use Illuminate\Support\Facades\Auth;

class InfoPembimbingController extends Controller
{
    public function show()
{
    try {
        // Dapatkan pengajuan PKL siswa yang sedang login yang memiliki status 'Diterima' dan pembimbing_id terisi
        $pengajuanPkl = PengajuanPKL::where('user_id', Auth::id())
            ->where('status', 'Diterima')
            ->whereNotNull('pembimbing_id')
            ->first();

        // Periksa apakah pengajuan PKL ditemukan
        if ($pengajuanPkl) {
            // Ambil informasi pembimbing berdasarkan pembimbing_id pada pengajuan PKL
            $infoPembimbing = $pengajuanPkl->pembimbing()->select('nip', 'name', 'email', 'nomer_telpon')->first();

            // Periksa apakah informasi pembimbing ditemukan
            if ($infoPembimbing) {
                return response()->json($infoPembimbing);
            } else {
                return response()->json(['message' => 'Data pembimbing tidak ditemukan untuk pengajuan PKL ini.'], 404);
            }
        } else {
            return response()->json(['message' => 'Pembimbing tidak tersedia untuk pengajuan PKL Anda atau pengajuan PKL Anda tidak diterima.'], 404);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => 'Gagal memuat informasi pembimbing.', 'message' => $e->getMessage()], 500);
    }
}

}
