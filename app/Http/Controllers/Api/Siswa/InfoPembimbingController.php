<?php

namespace App\Http\Controllers\Api\Siswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PengajuanPKL;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class InfoPembimbingController extends Controller
{
    public function show()
    {
        try {
            // Dapatkan pengajuan PKL siswa yang sedang login yang memiliki status 'Diterima' dan pembimbing_id terisi
            $pengajuanPkl = PengajuanPKL::where('user_id', Auth::id())
                ->where('status', 'Diterima')
                ->whereNotNull('pembimbing_id_1')
                ->whereNotNull('pembimbing_id_2')
                ->first();

            // Periksa apakah pengajuan PKL ditemukan
            if ($pengajuanPkl) {
                // Ambil informasi pembimbing berdasarkan pembimbing_id pada pengajuan PKL
                $infoPembimbing = [
                    'pembimbing 1' => User::select('nip', 'name', 'email', 'nomer_telpon')
                        ->where('id', $pengajuanPkl->pembimbing_id_1)
                        ->first(),
                    'pembimbing 2' => User::select('nip', 'name', 'email', 'nomer_telpon')
                        ->where('id', $pengajuanPkl->pembimbing_id_2)
                        ->first(),
                ];

                return response()->json($infoPembimbing);
            } else {
                return response()->json(['message' => 'Pembimbing tidak tersedia untuk pengajuan PKL Anda atau pengajuan PKL Anda tidak diterima.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat informasi pembimbing.', 'message' => $e->getMessage()], 500);
        }
    }
}