<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\Pembimbing;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class SppdController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua data pembimbing (users) yang memiliki role_id 3 dan sudah memiliki ID dalam tabel pembimbing
            $pembimbings = User::where('role_id', 3)
                ->whereHas('pembimbing', function ($query) {
                    $query->whereNotNull('id'); // Memeriksa apakah ada ID yang telah diinput dalam tabel pembimbing
                })
                ->leftJoin('pengajuan_pkl', function ($join) {
                    $join->on('users.id', '=', 'pengajuan_pkl.pembimbing_id_1')
                        ->orOn('users.id', '=', 'pengajuan_pkl.pembimbing_id_2');
                })
                ->whereNotNull('pengajuan_pkl.nama_perusahaan') // Menambahkan kondisi untuk mengecualikan nama_perusahaan yang null
                ->select('users.*', 'pengajuan_pkl.nama_perusahaan')
                ->get();

            // Loop melalui setiap pembimbing dan ambil data yang diperlukan
            $dataPembimbings = $pembimbings->map(function ($pembimbing) {
                return [
                    'user_id' => $pembimbing->id,
                    'nip' => $pembimbing->nip,
                    'name' => $pembimbing->name,
                    'email' => $pembimbing->email,
                    'jabatan' => $pembimbing->jabatan,
                    'pangkat' => $pembimbing->pangkat,
                    'nomer_telpon' => $pembimbing->nomer_telpon,
                    'nama_perusahaan' => $pembimbing->nama_perusahaan,
                ];
            });

            return response()->json($dataPembimbings, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching pembimbing data.' . $e->getMessage()], 500);
        }
    }
    public function detail($user_id)
    {
        try {
            // Ambil data pembimbing berdasarkan user_id
            $pembimbing = Pembimbing::where('user_id', $user_id)->first();

            // Periksa apakah pembimbing ditemukan
            if (!$pembimbing) {
                return response()->json(['error' => 'Pembimbing not found'], 404);
            }
            // Ambil data user berdasarkan user_id
            $user = User::find($user_id);


            // Kirim respons JSON dengan detail pembimbing
            return response()->json([
                'user_id' => $pembimbing->user_id,
                'name' => $user->name,
                'status' => $pembimbing->status,
                'tanggal' => $pembimbing->tanggal,
                'hari' => $pembimbing->hari,
                'waktu' => $pembimbing->waktu,
                'lamanya_perjalanan' => $pembimbing->lamanya_perjalanan,
            ], 200);
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tangani kesalahan dan kirim respons kesalahan ke frontend
            return response()->json(['error' => 'Failed to retrieve Pembimbing data: ' . $e->getMessage()], 500);
        }
    }



    public function generatesppd(Request $request)
    {
        // Validasi data yang diterima dari request
        $request->validate([
            'nosurat' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        try {
            // Terima nomor surat dan ID pengguna dari request
            $nosurat = $request->input('nosurat');
            $userId = $request->input('user_id');

            // Ambil data pembimbing dari tabel users berdasarkan ID
            $user = User::findOrFail($userId);

            // Ambil data pembimbing dari tabel pembimbing berdasarkan ID pengguna
            $pembimbing = Pembimbing::where('user_id', $userId)->firstOrFail();

            // Ambil data nama perusahaan dari pengajuanpkl yang terkait dengan pembimbing_id_1 atau pembimbing_id_2
            $nama_perusahaan = PengajuanPKL::where('pembimbing_id_1', $userId)
                ->orWhere('pembimbing_id_2', $userId)
                ->whereNotNull('nama_perusahaan') // Menambahkan kondisi untuk mengecualikan nama_perusahaan yang null
                ->value('nama_perusahaan');

            // Mengumpulkan data pembimbing dan data tambahan ke dalam satu array
            $data = [
                'nosurat' => $nosurat,
                'nip' => $user->nip,
                'name' => $user->name,
                'pangkat' => $user->pangkat,
                'jabatan' => $user->jabatan,
                'status' => $pembimbing->status,
                'hari' => $pembimbing->hari,
                'tanggal' => $pembimbing->tanggal,
                'waktu' => $pembimbing->waktu,
                'lamanya_perjalanan' => $pembimbing->lamanya_perjalanan,
                'nama_perusahaan' => $nama_perusahaan,
                // Tambahkan data lain yang diperlukan
            ];

            // Load view PDF dengan data yang telah ditentukan
            $pdf = PDF::loadView('pengajuan_sppd', $data);

            // Generate nama file PDF berdasarkan nama pengguna
            $pdfName = "surat_pengajuan_sppd_" . $user->name . ".pdf";



            // Kembalikan file PDF sebagai respons
            return $pdf->download($pdfName);

        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tangani kesalahan dan kirim respons kesalahan ke frontend

            return response()->json(['error' => 'Failed to generate and download PDF: ' . $e->getMessage()], 500);
        }
    }

}