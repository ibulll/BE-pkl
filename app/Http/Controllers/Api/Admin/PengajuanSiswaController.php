<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\User;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PengajuanSiswaController extends Controller
{
    public function getAllPengajuan()
    {
        try {
            // Mengambil semua data pengajuan_pkl dari database
            $pengajuan = PengajuanPKL::all();
    
            // Menambahkan URL CV dan Portofolio ke data pengajuan
            $pengajuan->each(function ($item) {
                $item->cv_url = $item->cv_url;
                $item->portofolio_url = $item->portofolio_url;
    
                // Ubah user_id menjadi nisn dan kelas
                $siswa = User::findOrFail($item->user_id);
                $item->name = $siswa->name;
                $item->nisn = $siswa->nisn;
                $item->kelas = $siswa->kelas;
            });
    
            // Filter agar hanya satu pengajuan yang ditampilkan untuk setiap user_id
            $filteredPengajuan = collect([]);
            $pengajuan->each(function ($item) use ($filteredPengajuan) {
                if (!$filteredPengajuan->contains('user_id', $item->user_id)) {
                    $filteredPengajuan->push($item);
                }
            });
    
            return response()->json(['data' => $filteredPengajuan]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving Pengajuan PKL: ' . $e->getMessage()], 500);
        }
    }
    
    

    public function updateStatus($id, Request $request)
    {
        try {
            // Find the record based on the 'id' field
            $pengajuan = PengajuanPKL::find($id);

            // Check if the record exists
            if (!$pengajuan) {
                return response()->json(['message' => 'Pengajuan not found'], 404);
            }

            // Update the status
            $pengajuan->status = $request->input('status');
            $pengajuan->save();

            return response()->json(['message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating status', 'error' => $e->getMessage()], 500);
        }
    }

    public function viewCV($id)
    {
        try {
            // Cari pengajuan PKL berdasarkan ID
            $pengajuan = PengajuanPKL::find($id);

            // Pastikan pengajuan PKL ditemukan
            if (!$pengajuan) {
                return response()->json(['error' => 'Pengajuan PKL tidak ditemukan'], 404);
            }

            // Path ke file CV di storage
            $cvPath = storage_path('app/public/' . $pengajuan->cv_file);

            // Periksa apakah file CV ada
            if (!Storage::exists($cvPath)) {
                return response()->json(['error' => 'File CV tidak ditemukan'], 404);
            }

            // Ambil konten file CV
            $cvContent = Storage::get($cvPath);

            // Set header untuk menampilkan CV di browser
            return response($cvContent)
                ->header('Content-Type', 'application/pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error viewing CV: ' . $e->getMessage()], 500);
        }
    }

    public function viewPortofolio($filename)
    {
        try {
            // Path ke file portofolio di storage
            $portofolioPath = 'file_portofolio/' . $filename;

            // Periksa apakah file portofolio ada
            if (!Storage::exists($portofolioPath)) {
                return response()->json(['error' => 'File Portofolio tidak ditemukan'], 404);
            }

            // Ambil file portofolio
            $file = Storage::get($portofolioPath);

            // Dapatkan tipe konten file
            $contentType = Storage::mimeType($portofolioPath);

            // Kirim respons dengan file portofolio
            return response($file, 200)->header('Content-Type', $contentType);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function detail($groupId)
    {
        try {
            // Cari detail pengajuan berdasarkan group_id
            $pengajuan = PengajuanPKL::where('group_id', $groupId)->get();

            $pengajuan->each(function ($item) {
    
                // Ubah user_id menjadi nisn dan kelas
                $siswa = User::findOrFail($item->user_id);
                $item->name = $siswa->name;
                $item->nisn = $siswa->nisn;
                $item->kelas = $siswa->kelas;
            });

            if ($pengajuan->isEmpty()) {
                return response()->json(['error' => 'Pengajuan dengan group_id ' . $groupId . ' tidak ditemukan'], 404);
            }

            return response()->json($pengajuan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error saat mengambil detail pengajuan: ' . $e->getMessage()], 500);
        }
    }









}