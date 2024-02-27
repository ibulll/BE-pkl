<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Jurnal;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JurnalController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua data jurnal
            $jurnals = Jurnal::all();

            return response()->json($jurnals, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching jurnal data.' . $e->getMessage()], 500);
        }
    }

    public function getJurnalSiswa($id = null)
    {
        try {
            // Jika $id tidak ditentukan, ambil semua data siswa
            if ($id === null) {
                // Ambil data siswa dengan role_id 4 dan sertakan pengajuan PKL jika ada
                $siswa = User::where('role_id', 4)->get(['id', 'name', 'nisn', 'kelas']);
    
                return response()->json($siswa, 200);
            } else {
                // Ambil data jurnal berdasarkan ID siswa
                $jurnals = Jurnal::where('user_id', $id)->get();
    
                // Ambil data siswa berdasarkan ID untuk informasi tambahan
                try {
                    $userJurnal = User::findOrFail($id, ['id', 'name', 'nisn', 'kelas']);
                } catch (ModelNotFoundException $exception) {
                    return response()->json(['error' => 'User not found.'], 404);
                }
    
                return response()->json([
                    'user_jurnal' => [
                        'id' => $userJurnal->id,
                        'name' => $userJurnal->name,
                        'nisn' => $userJurnal->nisn,
                        'kelas' => $userJurnal->kelas,
                        'jurnal' => $jurnals,
                    ],
                    'all_jurnals' => $jurnals,
                    'pageCount' => 1,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching jurnal data.' . $e->getMessage()], 500);
        }
    }    

}
