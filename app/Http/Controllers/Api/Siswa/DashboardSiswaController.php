<?php

namespace App\Http\Controllers\Api\Siswa;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonInterface;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardSiswaController extends Controller
{

    public function status()
{
    try {
        // Ambil data pengajuan PKL yang memiliki status sesuai dengan kriteria yang diinginkan dan user_id yang sesuai dengan pengguna yang sedang masuk
        $pendingApplications = PengajuanPKL::where('user_id', auth()->id())
            ->whereIn('status', ['Dipriksa', 'Diproses', 'Diterima', 'Ditolak'])
            ->get();

        // Periksa apakah ada pengajuan PKL yang sesuai dengan kriteria
        if ($pendingApplications->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data pengajuan PKL yang sesuai dengan kriteria yang diberikan.'], 404);
        }

        // Hitung waktu mundur untuk setiap pengajuan PKL yang diterima
        foreach ($pendingApplications as $application) {
            // Tambahkan 6 bulan ke tanggal akhir
            $endDate = Carbon::parse($application->endDate)->addMonths(6);
            $now = Carbon::now();
            
            // Hitung waktu countdown
            $countdown = $endDate->diffForHumans($now, [
                'parts' => 7, // Menampilkan semua bagian (bulan, minggu, hari, tanggal, jam, menit, detik)
                'join' => ', ',
                'syntax' => CarbonInterface::DIFF_ABSOLUTE
            ]);

            // Tambahkan waktu countdown ke data pengajuan PKL
            $application->countdown = $countdown;
        }

        // Kembalikan data pengajuan PKL sebagai JSON response
        return response()->json($pendingApplications);
    } catch (\Exception $e) {
        // Tangkap kesalahan dan kirimkan respons dengan status 500
        return response()->json(['error' => 'Gagal memuat data pengajuan PKL.', 'message' => $e->getMessage()], 500);
    }
}

    



    public function getDaftarAkunSiswa()
    {
        try {
            $daftarSiswa = User::where('role_id', 4)->get(); // Mengambil semua akun siswa
            return response()->json($daftarSiswa, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data.', 'message' => $e->getMessage()], 500);
        }
    }
}
