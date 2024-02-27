<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JurnalSiswaController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil semua data absensi
        $Jurnal = Jurnal::all();
        
        // Mengembalikan data absensi dalam bentuk response JSON
        return response()->json(['Jurnal' => $Jurnal]);
    }
}
