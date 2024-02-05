<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getCountData()
    {
        try {
            // Jumlah total pengguna
            $countUsers = User::count();

            // Jumlah siswa
            $countSiswa = User::where('role_id', '4')->count();

            // Jumlah pembimbing
            $countPembimbing = User::where('role_id', '3')->count();

            return response()->json([
                'countUsers' => $countUsers,
                'countSiswa' => $countSiswa,
                'countPembimbing' => $countPembimbing,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data.' . $e->getMessage(),
        ], 500);
        }
    }
}
