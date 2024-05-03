<?php

namespace App\Http\Controllers\Api\Siswa;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardSiswaController extends Controller
{

    public function status()
{
    // Ambil data pengajuan PKL yang sesuai dengan status yang diizinkan dan user_id yang sesuai dengan pengguna yang sedang masuk
    $allowedStatus = ['Diperiksa', 'Diproses', 'Diterima', 'Ditolak'];
    $pendingApplications = PengajuanPKL::whereIn('status', $allowedStatus)
        ->where('user_id', auth()->id())
        ->get();

    // Hitung waktu mundur untuk setiap pengajuan PKL
    foreach ($pendingApplications as $application) {
        Carbon::setLocale('id'); // Sesuaikan locale dengan zona waktu pengguna
        Carbon::setToStringFormat('j F Y H:i:s'); // Format string untuk tampilan countdown
        
        $endDate = Carbon::parse($application->endDate);
        $now = Carbon::now()->timezone('Asia/Jakarta'); // Sesuaikan dengan zona waktu pengguna

        // Periksa apakah status adalah "Diterima"
        if ($application->status === 'Diterima') {
            $endDate = $endDate->addMonths(6); // Tambahkan 6 bulan ke waktu akhir
        }
        
        // Ubah format tanggal menjadi ISO 8601
        $countdown = $endDate->toIso8601String();

        // Tambahkan waktu mundur ke pengajuan PKL
        $application->countdown = $countdown;
    }

    // Kembalikan data sebagai JSON response
    return response()->json($pendingApplications);
}



    public function getDaftarAkunSiswa()
    {
        try {
            $daftarSiswa = User::where('role_id', 4)->get(); // Mengambil semua akun siswa
            return response()->json($daftarSiswa, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching data.' . $e->getMessage()], 500);
        }
    }
}
