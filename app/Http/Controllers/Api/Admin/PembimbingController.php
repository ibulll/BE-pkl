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
    try {
        // Ambil semua data pengajuan PKL yang memiliki pembimbing (pengajuanPkl) dan saring hanya yang memiliki role_id 3 (pembimbing)
        $pengajuanPKLs = PengajuanPKL::with('pembimbing')->whereHas('pembimbing', function ($query) {
            $query->where('role_id', 3);
        })->get();

        // Loop melalui setiap pengajuan PKL dan ambil data pembimbing serta nama perusahaannya
        $pembimbings = $pengajuanPKLs->map(function ($pengajuanPKL) {

            return [
                'nip' => $pengajuanPKL->pembimbing->nip,
                'name' => $pengajuanPKL->pembimbing->name,
                'email' => $pengajuanPKL->pembimbing->email,
                'nomer_telpon' => $pengajuanPKL->pembimbing->nomer_telpon,
                'nama_perusahaan' => $pengajuanPKL->nama_perusahaan,
            ];
        });

        return response()->json($pembimbings, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error fetching pembimbing data.' . $e->getMessage()], 500);
    }
}




    public function assignToGroup(Request $request)
    {
        // Validasi request
        $request->validate([
            'pembimbing_id' => 'required|exists:users,id', // Validasi keberadaan pembimbing di tabel users
            'group_id' => 'required|exists:pengajuan_pkl,group_id',
        ]);

        try {
            // Dapatkan data pengajuan PKL berdasarkan group_id yang diberikan
            $pengajuanPkl = PengajuanPkl::where('group_id', $request->group_id)->get();

            // Loop melalui setiap entri pengajuan PKL dan atur pembimbing_id
            foreach ($pengajuanPkl as $pkl) {
                $pkl->pembimbing_id = $request->pembimbing_id; // Set pembimbing_id
                $pkl->save(); // Simpan perubahan
            }

            return response()->json(['message' => 'Pembimbing berhasil ditugaskan ke kelompok siswa']);
        } catch (\Exception $e) {
            // Tangkap kesalahan dan kirimkan respons JSON dengan kode status 500
            return response()->json(['error' => 'Gagal menugaskan pembimbing', 'message' => $e->getMessage()], 500);
        }
    }

    public function getDaftarSiswa(Request $request)
    {
        try {
            // Ambil data pengajuan PKL yang memiliki status 'Diterima' dan relasi pembimbing
            $pengajuan = PengajuanPkl::where('status', 'Diterima')
                ->with('pembimbing:id,name,nip,nomer_telpon,email')
                ->get();

            // Cek apakah data ditemukan
            if ($pengajuan->isEmpty()) {
                return response()->json(['error' => 'Data siswa dengan status Diterima tidak ditemukan'], 404);
            }

            return response()->json($pengajuan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error mencari data siswa' . $e->getMessage()], 500);
        }
    }
}
