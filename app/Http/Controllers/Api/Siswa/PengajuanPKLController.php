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
                ->get(['id', 'name']);

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
            // Validate incoming request data
            $validator = Validator::make($request->all(), [
                'nama' => 'required|string',
                // 'nisn' => 'required|string',
                // 'kelas' => 'required|string',
                'cv' => 'nullable|string',
                'portofolio' => 'nullable|string',
                'nama_perusahaan' => 'nullable|string',
                'email_perusahaan' => 'nullable|string',
                'alamat_perusahaan' => 'nullable|string',
                'perusahaan_id' => 'nullable|int', // Ubah menjadi wajib dipilih
                'file_cv' => 'file|required|mimes:pdf',
                'file_portofolio' => 'file|required|mimes:pdf',
            ]);

            // Check for validation errors
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // Fill the PengajuanPKL instance with the request data
            $pengajuan = new PengajuanPKL();
            $pengajuan->nama = $request->input('nama');
            $pengajuan->nisn = $request->input('nisn');
            $pengajuan->cv = $request->input('cv');
            $pengajuan->kelas = $request->input('kelas');
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

            // Set group_id to user_id of the authenticated user
            $pengajuan->group_id = auth()->id();

            // Ambil user_id teman berdasarkan nama yang dipilih
            $user = User::where('name', $request->input('nama'))->first();
            if (!$user) {
                return response()->json(['error' => 'User teman tidak ditemukan'], 404);
            }

            // Simpan user_id teman dalam pengajuan PKL
            $pengajuan->user_id = $user->id;
            $pengajuan->nisn = $user->nisn;
            $pengajuan->kelas = $user->kelas;

            // Store the uploaded 'fileCV' and 'filePortofolio'
            $pengajuan->file_cv = $request->file('file_cv')->store('file_cv', 'public');
            $pengajuan->file_portofolio = $request->file('file_portofolio')->store('file_portofolio', 'public');

            // Save the PengajuanPKL instance to the database
            $pengajuan->save();

            // Return success response with pengajuan data
            return response()->json([
                'message' => 'Pengajuan PKL berhasil disimpan',
                'pengajuan' => $pengajuan->toArray() // Or you can customize what data you want to return
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error storing Pengajuan PKL: ' . $e->getMessage()], 500);
        }
    }
}