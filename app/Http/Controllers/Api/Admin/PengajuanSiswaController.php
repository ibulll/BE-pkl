<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class PengajuanSiswaController extends Controller
{
    public function getAllPengajuan(Request $request)
    {
        try {
            $nama = $request->input('nama');

            // Lakukan pencarian nama siswa berdasarkan role_id jika parameter 'nama' diberikan
            if ($nama) {
                $siswa = User::where('role_id', 4)
                    ->where('name', 'like', '%' . $nama . '%')
                    ->get(['id', 'name']);

                // Cek apakah data ditemukan
                if ($siswa->isEmpty()) {
                    return response()->json(['error' => 'Siswa tidak ditemukan'], 404);
                }

                return response()->json($siswa);
            }

            // Mengambil semua data pengajuan_pkl dari database jika tidak ada parameter 'nama'
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
}
