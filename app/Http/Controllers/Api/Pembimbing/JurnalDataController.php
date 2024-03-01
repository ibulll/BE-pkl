<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\Jurnal;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JurnalDataController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan ID pembimbing yang sedang login
        $pembimbingId = Auth::id();
    
        // Mengambil data permohonan PKL yang terkait dengan pembimbing yang sedang login
        $permohonan = PengajuanPKL::where('pembimbing_id_1', $pembimbingId)
                                   ->orWhere('pembimbing_id_2', $pembimbingId)
                                   ->first();
    
        // Jika tidak ada permohonan PKL yang terkait dengan pembimbing yang sedang login, kembalikan respons kosong
        if (!$permohonan) {
            return response()->json(['message' => 'Anda belum dihubungkan dengan siswa untuk PKL'], 404);
        }
    
        // Mengambil ID siswa yang terkait dengan permohonan PKL
        $siswaIds = [$permohonan->user_id];
    
        // Mengambil semua data absensi yang terkait dengan siswa yang dibimbing oleh pembimbing tersebut
        $jurnal = Jurnal::whereIn('user_id', $siswaIds)->get();
    
        // Mengembalikan data absensi dalam bentuk respons JSON
        return response()->json(['Jurnal' => $jurnal]);
    }
}
