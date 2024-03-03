<?php

namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\Jurnal;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JurnalDataController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Mendapatkan ID pembimbing yang sedang login
            $pembimbingId = Auth::id();

            // Mengambil data permohonan PKL yang terkait dengan pembimbing yang sedang login
            $permohonan = PengajuanPKL::where('pembimbing_id_1', $pembimbingId)
                                       ->orWhere('pembimbing_id_2', $pembimbingId)
                                       ->first();

            // Jika tidak ada permohonan PKL yang terkait dengan pembimbing yang sedang login, kembalikan respons kosong
            if (!$permohonan) {
                return response()->json(['message' => 'Anda belum dihubungkan dengan siswa untuk PKL'], 404);
            }

            // Mengambil data siswa yang terkait dengan permohonan PKL
            $siswa = [
                'id' => $permohonan->user_id,
                'nama' => $permohonan->user->name,
                'kelas' => $permohonan->user->kelas,
                'nisn' => $permohonan->user->nisn,
            ];

            // Mengambil semua data jurnal yang terkait dengan siswa yang dibimbing oleh pembimbing tersebut
            $jurnal = Jurnal::where('user_id', $siswa['id'])->get();

            // Jika tidak ada jurnal untuk siswa yang dibimbing, kembalikan respons kosong
            if ($jurnal->isEmpty()) {
                return response()->json(['message' => 'Belum ada jurnal yang dibuat untuk siswa ini'], 404);
            }

            return response()->json(['siswa' => $siswa, 'jurnal' => $jurnal]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data jurnal.', 'message' => $e->getMessage()], 500);
        }
    }
}
