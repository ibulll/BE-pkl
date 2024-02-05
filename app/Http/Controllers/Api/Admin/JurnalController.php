<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Jurnal;
use App\Models\User;
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

    public function getJurnalSiswa()
    {
        try {
            // Ambil data siswa dengan role_id 4
            $siswa = User::where('role_id', 4)->get(['id', 'name']);

            return response()->json($siswa, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching siswa data.' . $e->getMessage()], 500);
        }
    }

    public function getJurnalSiswaById($id)
    {
        try {
            // Ambil data jurnal berdasarkan ID siswa
            $jurnals = Jurnal::where('siswa_id', $id)->get();

            // Ambil data siswa berdasarkan ID untuk informasi tambahan
            try {
                $userJurnal = User::findOrFail($id, ['id', 'name']);
            } catch (ModelNotFoundException $exception) {
                // Handle the case where the user is not found (provide a default response, log, etc.)
                return response()->json(['error' => 'User not found.'], 404);
            }

            return response()->json([
                'user_jurnal' => [
                    'id' => $userJurnal->id,
                    'name' => $userJurnal->name,
                    'jurnal' => $jurnals,
                ],
                'all_jurnals' => $jurnals,
                'pageCount' => 1, // Sesuaikan dengan logika paginasi Anda
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching jurnal data.' . $e->getMessage()], 500);
        }
    }
}
