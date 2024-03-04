<?php

namespace App\Http\Controllers\Api\Kaprog;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataPembimbingController extends Controller
{ 
    public function index(Request $request)
    {
        // Mengambil semua user yang memiliki role_id 3
        $users = User::where('role_id', 3)->get(['name','nip','nomer_telpon','jabatan','pangkat']);
        
        // Mengembalikan data user dalam bentuk response JSON
        return response()->json(['users' => $users]);
    }
    
}