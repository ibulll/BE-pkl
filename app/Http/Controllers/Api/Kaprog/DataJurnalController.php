<?php


namespace App\Http\Controllers\Api\Kaprog;

use App\Models\User;
use App\Models\Jurnal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataJurnalController extends Controller
{
    public function index()
    {
        // Mengambil semua data siswa dengan role_id 4
        $siswa = User::where('role_id', 4)->get(['id', 'name', 'nisn', 'kelas']);

        // Mengembalikan data siswa dalam bentuk response JSON
        return response()->json(['siswa' => $siswa]);
    }


    public function show($id)
    {
        // Mengambil data jurnal berdasarkan ID siswa
        $jurnals = Jurnal::where('user_id', $id)->get();

        // Mengembalikan data jurnal dalam bentuk response JSON
        return response()->json(['jurnals' => $jurnals]);
    }
}



