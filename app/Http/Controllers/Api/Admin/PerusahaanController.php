<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Perusahaan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PerusahaanController extends Controller
{


    public function getPerusahaan($id)
    {
        // Ambil detail perusahaan dari database berdasarkan ID perusahaan yang diberikan
        $perusahaan = Perusahaan::findOrFail($id);

        // Kirim kembali detail perusahaan sebagai respons JSON
        return response()->json([
            'email' => $perusahaan->email,
            'alamat' => $perusahaan->alamat,
        ]);
    }
    public function index()
    {
        $perusahaan = Perusahaan::all();
        return response()->json(['perusahaan' => $perusahaan], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string',
            'email_perusahaan' => 'required|email',
            'alamat_perusahaan' => 'required|string',
            'siswa_dibutuhkan' => 'required|integer',
        ]);
    
        $data = $request->only(['nama_perusahaan', 'email_perusahaan', 'alamat_perusahaan', 'siswa_dibutuhkan']);
    
        // Manipulasi data jika diperlukan
        // Misalnya, mengubah format data sebelum disimpan
    
        Perusahaan::create($data);
    
        return response()->json(['message' => 'Perusahaan created successfully'], 201);
    }
    

    public function show($id)
    {
        $perusahaan = Perusahaan::find($id);
        if (!$perusahaan) {
            return response()->json(['message' => 'Perusahaan not found'], 404);
        }
        return response()->json(['perusahaan' => $perusahaan], 200);
    }

    public function update(Request $request, $id)
    {
        $perusahaan = Perusahaan::find($id);
        if (!$perusahaan) {
            return response()->json(['message' => 'Perusahaan not found'], 404);
        }
        $data = $request->only(['nama_perusahaan', 'email_perusahaan', 'alamat_perusahaan', 'siswa_dibutuhkan']);
        $perusahaan->update($data);
        return response()->json(['message' => 'Perusahaan updated successfully'], 200);
    }

    public function destroy($id)
    {
        $perusahaan = Perusahaan::find($id);
        if (!$perusahaan) {
            return response()->json(['message' => 'Perusahaan not found'], 404);
        }
        $perusahaan->delete();
        return response()->json(['message' => 'Perusahaan deleted successfully'], 200);
    }
}
