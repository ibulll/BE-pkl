<?php

namespace App\Http\Controllers\Api\Kaprog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurnal;

class DashboardKaprogController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil total jumlah data user dengan role_id 3
        $totalUsersRole3 = User::where('role_id', 3)->count();

        // Mengambil total jumlah data user dengan role_id 4
        $totalUsersRole4 = User::where('role_id', 4)->count();
        
        // Mengambil total jumlah data jurnal
        $totalJurnals = Jurnal::count();
        
        // Mengembalikan total jumlah data user (role_id 3 dan 4 terpisah) dan total jumlah data jurnal dalam bentuk response JSON
        return response()->json(['total_users_role_3' => $totalUsersRole3, 'total_users_role_4' => $totalUsersRole4, 'total_jurnals' => $totalJurnals]);
    }
}
