<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Pembimbing;
use App\Models\PengajuanPKL;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PembimbingController extends Controller
{
    public function index()
    {
        $pembimbing = Pembimbing::all();
        return response()->json($pembimbing);
    }

    public function assignToGroup(Request $request)
    {
        // Validasi request
        $request->validate([
            'pembimbing_id' => 'required|exists:pembimbings,id',
            'group_id' => 'required|exists:pengajuan_pkl,id',
        ]);

        // Ambil pengajuan PKL yang terkait dengan pembimbing
        $pengajuanPkl = PengajuanPKL::where('pembimbing_id', $request->pembimbing_id)->first();

        if (!$pengajuanPkl) {
            return response()->json(['error' => 'Tidak ada pengajuan PKL yang terkait dengan pembimbing ini'], 400);
        }

        // Ambil group_id dari pengajuan PKL
        $group_id = $pengajuanPkl->group_id;

        // Ambil pembimbing
        $pembimbing = Pembimbing::findOrFail($request->pembimbing_id);

        // Periksa apakah grup sudah memiliki dua pembimbing
        $group = PengajuanPKL::findOrFail($group_id);
        if ($group->pembimbings()->count() >= 2) {
            return response()->json(['error' => 'Grup sudah memiliki dua pembimbing'], 400);
        }

        // Lakukan penugasan pembimbing ke grup
        $group->pembimbings()->attach($pembimbing);

        return response()->json(['message' => 'Pembimbing berhasil ditugaskan ke grup']);
    }

    public function getDaftarPembimbing(Request $request)
    {
        try {
            $nama = $request->input('nama');

            // Lakukan pencarian nama siswa berdasarkan role_id
            $pembimbing = User::where('role_id', 3)
                ->where('name', 'like', '%' . $nama . '%')
                ->get(['id', 'name']);

            // Cek apakah data ditemukan
            if ($pembimbing->isEmpty()) {
                return response()->json(['error' => 'Pembimbing tidak ditemukan'], 404);
            }

            return response()->json($pembimbing);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error mencari pembimbing'], 500);
        }
    }
}
