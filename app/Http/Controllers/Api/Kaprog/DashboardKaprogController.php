<?php

namespace App\Http\Controllers\Api\Kaprog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurnal; // Mengimpor model Jurnal

class DashboardKaprogController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil total jumlah data user dengan role_id 4 dan 3
        $totalUsers = User::whereIn('role_id', [3, 4])->count();
        
        // Mengambil total jumlah data jurnal
        $totalJurnals = Jurnal::count();
        
        // Mengembalikan total jumlah data user dan total jumlah data jurnal dalam bentuk response JSON
        return response()->json(['total_users' => $totalUsers, 'total_jurnals' => $totalJurnals]);
    }
}
