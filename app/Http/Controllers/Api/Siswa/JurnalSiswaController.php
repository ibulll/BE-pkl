<?php

namespace App\Http\Controllers\Api\Siswa;

use Carbon\Carbon;
use App\Models\Jurnal;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JurnalSiswaController extends Controller
{

    public function index(Request $request)
{
    try {

        $userId = Auth::id();

        // Ambil semua data jurnal yang terkait dengan user_id
        $journals = Jurnal::where('user_id', $userId)->get();

        return response()->json($journals, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error fetching jurnal data.' . $e->getMessage()], 500);
    }
}


public function update(Request $request, $id)
{
    // Temukan jurnal berdasarkan ID
    $journal = Jurnal::findOrFail($id);

    // Pastikan bahwa user hanya dapat mengubah jurnal yang dimilikinya
    if ($journal->user_id != Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    // Simpan status jurnal sebelum pembaruan
    $previousStatus = $journal->status;

    // Update jurnal dengan data yang diberikan
    $journal->update($request->all());

    // Simpan status jurnal sebelum pembaruan
    $previousStatus = $journal->status;

    // Update jurnal dengan data yang diberikan
    $journal->update($request->all());

    // Jika status diubah menjadi 'selesai' dan sebelumnya bukan 'selesai', isi waktu_selesai dan tanggal_selesai
    if ($request->has('status') && $request->status == 'selesai' && $previousStatus != 'selesai') {
        $now = Carbon::now()->timezone('Asia/Jakarta');
        $journal->waktu_selesai = $now->toTimeString(); // Format waktu (HH:MM:SS)
        $journal->tanggal_selesai = $now->toDateString(); // Format tanggal (YYYY-MM-DD)
        $journal->save();
    }

    return response()->json($journal, 200);
}

    public function show($id)
    {
        try {
            // Assuming you have an Eloquent model named JurnalSiswa
            $journal = Jurnal::findOrFail($id);

            return response()->json($journal);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Journal not found'], 404);
        }
    }

    public function store(Request $request)
    {
        // Mendapatkan ID user yang sedang login
        $userId = Auth::id();
    
        // Memeriksa apakah pengguna memiliki permohonan PKL yang sudah diterima
        $permohonan = PengajuanPKL::where('user_id', $userId)
                                    ->where('status', 'Diterima')
                                    ->first();
    
        if (!$permohonan) {
            return response()->json(['message' => 'Anda belum memiliki permohonan PKL yang diterima'], 400);
        }
    
        // Menyertakan user_id dalam data jurnal yang akan disimpan
        $data = $request->all();
        $data['user_id'] = $userId;
    
        // Mendapatkan waktu dan tanggal saat ini menggunakan Carbon dengan zona waktu Jakarta
        $now = Carbon::now()->timezone('Asia/Jakarta');
        $data['waktu_mulai'] = $now->toTimeString(); // Format waktu (HH:MM:SS)
        $data['tanggal_mulai'] = $now->toDateString(); // Format tanggal (YYYY-MM-DD)
    
        $journal = Jurnal::create($data);
    
        return response()->json($journal, 201);
    }
    

public function destroy($id)
{
    try {
        // Cari data jurnal berdasarkan ID
        $journal = Jurnal::find($id);

        // Jika data jurnal tidak ditemukan
        if (!$journal) {
            return response()->json(['message' => 'Journal not found'], 404);
        }

        // Hapus data jurnal
        $journal->delete();

        // Berikan respons sukses
        return response()->json(['message' => 'Journal deleted successfully'], 200);
    } catch (\Exception $e) {
        // Tangani kesalahan
        return response()->json(['error' => 'Failed to delete journal. ' . $e->getMessage()], 500);
    }

}
}