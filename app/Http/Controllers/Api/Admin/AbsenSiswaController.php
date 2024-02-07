<?php
namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AbsenSiswaController extends Controller
{
    public function index()
    {
        $siswaList = User::select('id', 'nisn', 'name')->where('role', 'siswa')->get();
        return response()->json($siswaList);
    }

    public function show($userId)
    {
        $siswa = User::find($userId);
        if (!$siswa || $siswa->role !== 'siswa') {
            return response()->json(['message' => 'Siswa not found'], 404);
        }

        $absensiList = $siswa->absensi()->get();
        return response()->json($absensiList);
    }
}
