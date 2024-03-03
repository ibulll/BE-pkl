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
        $endDate = Carbon::parse($application->endDate);
        $now = Carbon::now();
        $countdown = $endDate->diffForHumans($now, [
            'parts' => 5, // Menampilkan semua bagian (tahun, bulan, hari, jam, menit)
            'join' => ', ',
        ]);

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