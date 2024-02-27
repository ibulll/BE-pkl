<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\User;
use App\Models\Pembimbing;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SppdController extends Controller
{
    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'status' => 'required|string|in:Monitoring,Penjemputan,Pengantaran',
            'tanggal' => 'required|string',
            'hari' => 'required|string',
            'waktu' => 'required|string',
        ]);

        // Mendapatkan informasi pembimbing yang memiliki role_id 3 (pembimbing)
        $pembimbing = User::whereHas('roles', function ($query) {
            $query->where('id', 3); // Role ID 3 diasumsikan sebagai role untuk pembimbing
        })->first();

        // Pastikan pembimbing tersedia
        if (!$pembimbing) {
            return response()->json(['message' => 'Pembimbing tidak ditemukan'], 404);
        }

        // Cek apakah pembimbing memiliki pengajuan PKL terkait
        $pengajuanPKL = PengajuanPKL::where('group_id', $pembimbing->id)->first();

        // Jika pembimbing tidak memiliki pengajuan PKL terkait
        if (!$pengajuanPKL) {
            return response()->json(['message' => 'Anda tidak membimbing grup manapun, Anda tidak dapat mengajukan SPPD'], 400);
        }

        // Simpan data ke dalam tabel Pembimbing
        $data = [
            'user_id' => $pembimbing->id,
            'status' => $request->input('status'),
            'tanggal' => $request->input('tanggal'),
            'hari' => $request->input('hari'),
            'waktu' => $request->input('waktu'),
        ];

        Pembimbing::create($data);

        // Kirim respons ke frontend
        return response()->json(['message' => 'Data berhasil disimpan']);
    }
}