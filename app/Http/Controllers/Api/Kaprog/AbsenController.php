<?php

namespace App\Http\Controllers\Api\Kaprog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi; // Mengimpor model Absensi

class AbsenController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil semua data absensi
        $absensi = Absensi::all();
        
        // Mengembalikan data absensi dalam bentuk response JSON
        return response()->json(['absensi' => $absensi]);
    }
}
