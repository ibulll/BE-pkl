<?php


namespace App\Http\Controllers\Api\Pembimbing;

use App\Models\Absensi;
use App\Models\PengajuanPKL;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DataAbsenController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan ID pembimbing yang sedang login
        $pembimbingId = Auth::id();

        // Mengambil data pengajuan PKL yang memiliki pembimbing_id yang sesuai
        $pengajuanPkl = PengajuanPKL::where('pembimbing_id_1', $pembimbingId)
            ->orWhere('pembimbing_id_2', $pembimbingId)
            ->get();

        // Kumpulkan id user siswa dari pengajuan PKL yang dibimbing oleh pembimbing
        $siswaIds = $pengajuanPkl->pluck('user_id')->toArray();

        // Ambil data siswa berdasarkan id yang terkumpul, hanya ambil id, nisn, name, kelas, dan email
        $siswa = User::whereIn('id', $siswaIds)->get(['id', 'nisn', 'name', 'kelas', 'email']);

        // Jika tidak ada siswa yang terhubung dengan pembimbing, kembalikan respons kosong
        if ($siswa->isEmpty()) {
            return response()->json(['message' => 'Anda belum dihubungkan dengan siswa untuk PKL'], 404);
        }

        // Mengumpulkan data absensi terbaru untuk setiap siswa yang dibimbing
        $data = [];
        foreach ($siswa as $s) {
            // Memeriksa apakah siswa terkait dengan pengajuan PKL yang memiliki pembimbing yang sedang login
            $isRelated = $pengajuanPkl->where('user_id', $s->id)->isNotEmpty();
            if ($isRelated) {
                $absensi = Absensi::where('user_id', $s->id)
                    ->latest() // Mengambil entri absensi terbaru untuk setiap user_id
                    ->first();
                if ($absensi) {
                    // Memeriksa apakah foto absensi ada
                    $fotoUrl = $absensi->foto ? asset('storage/' . $absensi->foto) : '';

                    $data[] = [
                        'user_id' => $s->id,
                        'nama' => $s->name,
                        'nisn' => $s->nisn,
                        'kelas' => $s->kelas,
                        'email' => $s->email,
                        'absensi' => [
                            'latitude' => $absensi->latitude,
                            'longitude' => $absensi->longitude,
                            'foto' => $fotoUrl,
                            'tanggal_absen' => $absensi->tanggal_absen,
                            'waktu_absen' => $absensi->waktu_absen,
                        ],
                    ];
                } else {
                    // Jika tidak ada data absensi, tambahkan entri dengan data absensi kosong
                    $data[] = [
                        'user_id' => $s->id,
                        'nama' => $s->name,
                        'nisn' => $s->nisn,
                        'kelas' => $s->kelas,
                        'email' => $s->email,
                        'absensi' => null,
                    ];
                }
            }
        }

        // Mengembalikan data dalam bentuk respons JSON
        return response()->json(['data' => $data]);
    }
}
