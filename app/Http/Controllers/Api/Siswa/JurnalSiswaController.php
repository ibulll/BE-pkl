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

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan' => 'required',
            'status' => 'required',
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
    
}
