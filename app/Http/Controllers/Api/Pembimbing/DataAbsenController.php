<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataAbsenController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil semua data absensi
        $absensi = Absensi::all();
        
        // Mengembalikan data absensi dalam bentuk response JSON
        return response()->json(['absensi' => $absensi]);
    }
}
