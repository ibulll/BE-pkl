<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;;

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
            });

            return response()->json(['data' => $pengajuan]);
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

            // Kembalikan URL CV
            return response()->json(['cv_url' => asset('storage/' . $pengajuan->file_cv)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error viewing CV: ' . $e->getMessage()], 500);
        }
    }

    public function viewPortofolio($id)
    {
        try {
            // Cari pengajuan PKL berdasarkan ID
            $pengajuan = PengajuanPKL::find($id);

            // Pastikan pengajuan PKL ditemukan
            if (!$pengajuan) {
                return response()->json(['error' => 'Pengajuan PKL tidak ditemukan'], 404);
            }

            // Kembalikan URL Portofolio
            return response()->json(['portofolio_url' => asset('storage/' . $pengajuan->file_portofolio)]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error viewing Portofolio: ' . $e->getMessage()], 500);
        }
    }
}
