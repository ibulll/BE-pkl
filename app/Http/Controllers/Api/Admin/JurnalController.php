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
                // Ambil data siswa dengan role_id 4
                $siswa = User::where('role_id', 4)->get(['id', 'name']);

                // Ambil NISN dan kelas dari tabel PengajuanPKL
                $pengajuanPKL = PengajuanPKL::whereIn('user_id', $siswa->pluck('id'))->get(['user_id', 'nisn', 'kelas']);

                // Gabungkan NISN dan kelas dengan data siswa
                $siswa->transform(function ($user) use ($pengajuanPKL) {
                    $pengajuan = $pengajuanPKL->where('user_id', $user->id)->first();
                    $user->nisn = $pengajuan ? $pengajuan->nisn : null;
                    $user->kelas = $pengajuan ? $pengajuan->kelas : null;
                    return $user;
                });

                return response()->json($siswa, 200);
            } else {
                // Ambil data jurnal berdasarkan ID siswa
                $jurnals = Jurnal::where('user_id', $id)->get();

                // Ambil data siswa berdasarkan ID untuk informasi tambahan
                try {
                    $userJurnal = User::findOrFail($id, ['id', 'name']);
                } catch (ModelNotFoundException $exception) {
                    // Handle the case where the user is not found (provide a default response, log, etc.)
                    return response()->json(['error' => 'User not found.'], 404);
                }

                // Ambil NISN dan kelas dari tabel PengajuanPKL
                $pengajuan = PengajuanPKL::where('user_id', $id)->first();
                $nisn = $pengajuan ? $pengajuan->nisn : null;
                $kelas = $pengajuan ? $pengajuan->kelas : null;

                // Jika tidak ada entri PengajuanPKL, berikan respons dengan NISN dan kelas kosong
                if (!$nisn) {
                    return response()->json([
                        'user_jurnal' => [
                            'id' => $userJurnal->id,
                            'name' => $userJurnal->name,
                            'nisn' => null, // NISN kosong
                            'kelas' => null, // Kelas kosong
                            'jurnal' => $jurnals,
                        ],
                        'all_jurnals' => $jurnals,
                        'pageCount' => 1, // Sesuaikan dengan logika paginasi Anda
                    ], 200);
                }

                return response()->json([
                    'user_jurnal' => [
                        'id' => $userJurnal->id,
                        'name' => $userJurnal->name,
                        'nisn' => $nisn, // Tambahkan NISN ke respons
                        'kelas' => $kelas, // Tambahkan kelas ke respons
                        'jurnal' => $jurnals,
                    ],
                    'all_jurnals' => $jurnals,
                    'pageCount' => 1, // Sesuaikan dengan logika paginasi Anda
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching jurnal data.' . $e->getMessage()], 500);
        }
    }


}
