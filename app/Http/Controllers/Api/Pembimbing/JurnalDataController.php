<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\User;
use App\Models\Jurnal;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JurnalDataController extends Controller
{
    public function all()
    {
        try {
            // Ambil semua data jurnal
            $jurnals = Jurnal::all();

            return response()->json($jurnals, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching jurnal data.' . $e->getMessage()], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            // Mendapatkan ID pembimbing yang sedang login
            $pembimbingId = Auth::id();

            // Mengambil data permohonan PKL yang terkait dengan pembimbing yang sedang login
            $permohonans = PengajuanPKL::where('pembimbing_id_1', $pembimbingId)
                ->orWhere('pembimbing_id_2', $pembimbingId)
                ->get();

            // Jika tidak ada permohonan PKL yang terkait dengan pembimbing yang sedang login, kembalikan respons kosong
            if ($permohonans->isEmpty()) {
                return response()->json(['message' => 'Anda belum dihubungkan dengan siswa untuk PKL'], 404);
            }

            // Mengambil data siswa yang terkait dengan permohonan PKL
            $siswas = $permohonans->map(function ($permohonan) {
                return [
                    'id' => $permohonan->user_id,
                    'nama' => $permohonan->user->name,
                    'kelas' => $permohonan->user->kelas,
                    'nisn' => $permohonan->user->nisn,
                ];
            });

            return response()->json(['siswas' => $siswas]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data siswa.', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            // Ambil data jurnal berdasarkan ID siswa
            $jurnals = Jurnal::where('user_id', $id)->get();

            // Ambil data siswa berdasarkan ID untuk informasi tambahan
            try {
                $userJurnal = User::findOrFail($id, ['id', 'name', 'nisn', 'kelas']);
            } catch (ModelNotFoundException $exception) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Mendapatkan isi jurnal berdasarkan ID siswa
            $jurnalDetails = $jurnals->map(function ($jurnal) {
                return [
                    'id' => $jurnal->id,
                    'kegiatan' => $jurnal->kegiatan,
                    'status' => $jurnal->status,
                    'waktu' => $jurnal->waktu,
                    'tanggal' => $jurnal->tanggal,
                ];
            });

            return response()->json([
                'user_jurnal' => [
                    'id' => $userJurnal->id,
                    'name' => $userJurnal->name,
                    'nisn' => $userJurnal->nisn,
                    'kelas' => $userJurnal->kelas,
                    'jurnal' => $jurnalDetails, // Menggunakan variabel $jurnalDetails yang berisi detail jurnal
                ],
                'all_jurnals' => $jurnals,
                'pageCount' => 1,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching jurnal data.' . $e->getMessage()], 500);
        }
    }
}
