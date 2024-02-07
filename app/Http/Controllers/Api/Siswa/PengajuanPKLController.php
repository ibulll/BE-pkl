<?php

namespace App\Http\Controllers\Api\Siswa;

use App\Models\User;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Facades\Validator;


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
    


public function store(Request $request)
{
    try {
        // Validate incoming request data
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'nisn' => 'required|string',
            'cv' => 'string',
            'portofolio' => 'string',
            'email' => 'required|email',
            'alamat' => 'required|string',
            'file_cv' => 'file|required|mimes:pdf',
            'file_portofolio' => 'file|required|mimes:pdf',
        ]);

          // Fill the PengajuanPKL instance with the request data
          $pengajuan = new PengajuanPKL();
          $pengajuan->nama = $request->input('nama');
          $pengajuan->nisn = $request->input('nisn');
          $pengajuan->cv = $request->input('cv');
          $pengajuan->portofolio = $request->input('portofolio');
          $pengajuan->email = $request->input('email');
          $pengajuan->alamat = $request->input('alamat');
          $pengajuan->user_id = Auth::id();

        // Check if 'fileCV' is present and valid
        if ($request->hasFile('file_cv') && $request->file('file_cv')->isValid()) {
            // Store the uploaded 'fileCV'
            $pengajuan->file_cv = $request->file('file_cv')->store('cv_files', 'public');
        } else {
            // Handle the case where 'fileCV' is missing or not valid
            return response()->json(['error' => 'Invalid or missing fileCV'], 400);
        }

        // Check if 'filePortofolio' is present and valid
        if ($request->hasFile('file_portofolio') && $request->file('file_portofolio')->isValid()) {
            // Store the uploaded 'filePortofolio'
            $pengajuan->file_portofolio = $request->file('file_portofolio')->store('portofolio_files', 'public');
        } else {
            // Handle the case where 'filePortofolio' is missing or not valid
            return response()->json(['error' => 'Invalid or missing filePortofolio'], 400);
        }

        // Save the PengajuanPKL instance to the database
        $pengajuan->save();

        return response()->json(['message' => 'Pengajuan PKL berhasil disimpan'], 201);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error storing Pengajuan PKL: ' . $e->getMessage()], 500);
    }
}
}