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
            'pembimbing_id' => 'required|exists:pembimbing,id',
            'group_id' => 'required|exists:pengajuan_pkl,group_id',
        ]);

        try {
            // Temukan pengajuan PKL yang terkait dengan kelompok
            $pengajuanPkl = PengajuanPKL::where('group_id', $request->group_id)->get();

            // Temukan pembimbing
            $pembimbing = Pembimbing::findOrFail($request->pembimbing_id);

            // Tugaskan pembimbing ke setiap pengajuan PKL dalam kelompok
            foreach ($pengajuanPkl as $pkl) {
                $pkl->pembimbing_id = $pembimbing->id;
                $pkl->save();
            }

            // Update kolom group_id pada tabel pembimbing
            $pembimbing->group_id = $request->group_id;
            $pembimbing->save();

            return response()->json(['message' => 'Pembimbing berhasil ditugaskan ke kelompok siswa']);
        } catch (\Exception $e) {
            // Tangkap kesalahan dan kirimkan respons JSON dengan kode status 500
            return response()->json(['error' => 'Gagal menugaskan pembimbing', 'message' => $e->getMessage()], 500);
        }
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
