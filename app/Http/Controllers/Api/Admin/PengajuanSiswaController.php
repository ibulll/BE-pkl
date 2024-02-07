<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
;

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
    
}