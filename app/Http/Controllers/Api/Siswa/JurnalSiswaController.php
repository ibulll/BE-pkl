<?php

namespace App\Http\Controllers\Api\Siswa;

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
    $journal = Jurnal::find($id);
    if (!$journal) {
        return response()->json(['message' => 'Journal not found'], 404);
    }

    // Update the journal entry with data from the request
    $journal->update($request->all());

    // Return a success response
    return response()->json(['message' => 'Journal updated successfully']);
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
    $request->validate([
        'waktu' => 'required',
        'tanggal' => 'required',
    ]);

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
    $data = array_merge($request->all(), ['user_id' => $userId]);

    $journal = Jurnal::create($data);

    return response()->json($journal, 201);
}

    
}