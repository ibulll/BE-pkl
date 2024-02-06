<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JurnalSiswaController extends Controller
{

    public function index()
    {
        $journals = Jurnal::all();
        return response()->json($journals);
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

        // Menyertakan users_id dalam data jurnal yang akan disimpan
        $data = array_merge($request->all(), ['user_id' => $userId]);

        $journal = Jurnal::create($data);

        return response()->json($journal, 201);
    }

    public function destroy($id)
    {
        $journal = Jurnal::find($id);
        if (!$journal) {
            return response()->json(['message' => 'Journal not found'], 404);
        }

        $journal->delete();

        return response()->json(['message' => 'Journal deleted successfully']);
    }
    
}
