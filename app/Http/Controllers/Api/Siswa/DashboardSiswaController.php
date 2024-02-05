<?php

namespace App\Http\Controllers\Api\Siswa;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardSiswaController extends Controller
{
    public function getDaftarAkunSiswa()
    {
        try {
            $daftarSiswa = User::where('role_id', 4)->get(); // Mengambil semua akun siswa
            return response()->json($daftarSiswa, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data.' . $e->getMessage()], 500);
        }
    }
}