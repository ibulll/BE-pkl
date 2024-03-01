<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\User;
use App\Models\Pembimbing;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PengajuanSppdController extends Controller
{
    public function store(Request $request)
{
    // Mendapatkan ID user yang sedang login
    $userId = Auth::id();

    // Validasi request
    $request->validate([
        'status' => 'required|string|in:Monitoring,Penjemputan,Pengantaran',
        'tanggal' => 'required|string',
        'hari' => 'required|string',
        'waktu' => 'required|string',
        'lamanya_perjalanan' => 'required|string',
    ]);

    try {
        // Simpan data ke dalam tabel Pembimbing
        $data = [
            'user_id' => $userId, // Gunakan ID pengguna yang sedang login
            'status' => $request->input('status'),
            'tanggal' => $request->input('tanggal'),
            'hari' => $request->input('hari'),
            'waktu' => $request->input('waktu'),
            'lamanya_perjalanan' => $request->input('lamanya_perjalanan'),
        ];

        Pembimbing::create($data);

        // Kirim respons ke frontend
        return response()->json(['message' => 'Data berhasil disimpan']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error storing Pembimbing data: ' . $e->getMessage()], 500);
    }
}
}