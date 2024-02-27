<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Models\User;
use App\Models\Perusahaan;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Tambahkan use statement untuk menggunakan Storage

class PengajuanPKLController extends Controller
{
    // ...

    public function getDaftarSiswa(Request $request)
    {
        try {
            $nama = $request->input('nama');

            // Lakukan pencarian nama siswa berdasarkan role_id
            $siswa = User::where('role_id', 4)
                ->where('name', 'like', '%' . $nama . '%')
                ->get(['id', 'name', 'nisn', 'kelas']);

            // Cek apakah data ditemukan
            if ($siswa->isEmpty()) {
                return response()->json(['error' => 'Siswa tidak ditemukan'], 404);
            }

            return response()->json($siswa);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error searching for students'], 500);
        }
    }

    public function getDaftarKelas()
    {
        try {
            $daftarKelas = [
                'XII PPLG 1',
                'XII PPLG 2',
                'XII PPLG 3',
            ];

            return response()->json($daftarKelas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching daftar kelas: ' . $e->getMessage()], 500);
        }
    }

    public function getDaftarPerusahaan()
    {
        try {
            $perusahaan = Perusahaan::all();
            return response()->json($perusahaan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching daftar perusahaan: ' . $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            // Validasi data permohonan yang masuk
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|int', // Ubah validasi menjadi user_id yang diberikan
                'cv' => 'nullable|string',
                'portofolio' => 'nullable|string',
                'nama_perusahaan' => 'nullable|string',
                'email_perusahaan' => 'nullable|string',
                'alamat_perusahaan' => 'nullable|string',
                'perusahaan_id' => 'nullable|int', // Wajib dipilih jika perusahaan sudah ada
                'file_cv' => 'file|required|mimes:pdf',
                'file_portofolio' => 'file|required|mimes:pdf',
            ]);
    
            // Periksa apakah ada kesalahan validasi
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
    
            // Ambil user dari tabel users berdasarkan user_id yang dimasukkan
            $user = User::find($request->input('user_id'));
            if (!$user) {
                return response()->json(['error' => 'User tidak ditemukan'], 404);
            }
    
            // Periksa apakah pengguna sudah memiliki permohonan yang sedang dalam status "Diperiksa", "Diproses", atau "Diterima"
            if ($user->pengajuanPKL()->whereIn('status', ['Diperiksa', 'Diproses', 'Diterima'])->exists()) {
                // Jika pengguna memiliki permohonan yang sedang dalam status yang tidak diizinkan, kembalikan respons dengan pesan kesalahan
                return response()->json(['error' => 'Anda sudah memiliki permohonan yang sedang dalam proses atau diterima.'], 400);
            }
    
            // Periksa apakah pengguna memiliki permohonan yang pernah ditolak
            if ($user->pengajuanPKL()->where('status', 'Ditolak')->exists()) {
                // Jika pengguna memiliki permohonan yang pernah ditolak, izinkan mereka untuk mengajukan permohonan baru
                // Anda bisa menambahkan logika tambahan di sini, seperti menghapus permohonan sebelumnya atau memperbarui statusnya
            }
    
            // Buat instance PengajuanPKL dengan data dari permohonan
            $pengajuan = new PengajuanPKL();
    
            // Tetapkan user_id ke ID pengguna yang ditemukan
            $pengajuan->user_id = $user->id;
    
            // Isi data permohonan dari data pengguna yang ditemukan
            $pengajuan->cv = $request->input('cv');
            $pengajuan->portofolio = $request->input('portofolio');
            $pengajuan->perusahaan_id = $request->input('perusahaan_id');
    
            // Jika perusahaan_id tidak kosong, artinya siswa memilih perusahaan yang sudah disediakan
            if ($request->filled('perusahaan_id')) {
                $perusahaan = Perusahaan::find($request->input('perusahaan_id'));
                if (!$perusahaan) {
                    return response()->json(['error' => 'Perusahaan tidak ditemukan'], 404);
                }
                // Isi informasi perusahaan dari perusahaan yang dipilih
                $pengajuan->nama_perusahaan = $perusahaan->nama_perusahaan;
                $pengajuan->email_perusahaan = $perusahaan->email_perusahaan;
                $pengajuan->alamat_perusahaan = $perusahaan->alamat_perusahaan;
            } else {
                // Jika perusahaan_id kosong, artinya siswa ingin mengisi perusahaannya sendiri
                $pengajuan->nama_perusahaan = $request->input('nama_perusahaan');
                $pengajuan->email_perusahaan = $request->input('email_perusahaan');
                $pengajuan->alamat_perusahaan = $request->input('alamat_perusahaan');
            }
    
            // Set group_id ke user_id pengguna yang terautentikasi
            $pengajuan->group_id = auth()->id();
    
            // Simpan berkas 'fileCV' dan 'filePortofolio' yang diunggah
            $pengajuan->file_cv = $request->file('file_cv')->store('file_cv', 'public');
            $pengajuan->file_portofolio = $request->file('file_portofolio')->store('file_portofolio', 'public');

            $pengajuan->save();
    
            // Tampilkan respons sukses dengan data permohonan dan informasi tambahan
            return response()->json([
                'message' => 'Pengajuan PKL berhasil disimpan',
                'pengajuan' => $pengajuan->toArray(), // Atau Anda dapat menyesuaikan data yang ingin ditampilkan
                'nisn' => $user->nisn,
                'kelas' => $user->kelas
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error storing Pengajuan PKL: ' . $e->getMessage()], 500);
        }
    }    
}