<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getCountData()
    {
        try {
            // Jumlah total pengguna
            $countUsers = User::count();

            // Jumlah siswa
            $countSiswa = User::where('role', 'siswa')->count();

            // Jumlah pembimbing
            $countPembimbing = User::where('role', 'pembimbing')->count();

            return response()->json([
                'countUsers' => $countUsers,
                'countSiswa' => $countSiswa,
                'countPembimbing' => $countPembimbing,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data.'], 500);
        }
    }
}