<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Pembimbing;
use App\Models\PengajuanPKL;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PembimbingController extends Controller
{
    public function index()
    {
        try {
            // Ambil semua data pembimbing (users) yang memiliki role_id 3 beserta data PengajuanPKL yang terkait
            $pembimbings = User::where('role_id', 3)
                ->leftJoin('pengajuan_pkl', function ($join) {
                    $join->on('users.id', '=', 'pengajuan_pkl.pembimbing_id_1')
                        ->orOn('users.id', '=', 'pengajuan_pkl.pembimbing_id_2');
                })
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
            return response()->json(['error' => 'Error fetching pembimbing data: ' . $e->getMessage()], 500);
        }
    }

    public function assignToGroup(Request $request)
    {
        // Validasi request
        $request->validate([
            'pembimbing_id' => 'required|exists:users,id', // Validasi keberadaan pembimbing di tabel users
            'group_id' => 'required|exists:pengajuan_pkl,group_id',
        ]);

        try {
            // Dapatkan data pengajuan PKL berdasarkan group_id yang diberikan
            $pengajuanPkl = PengajuanPkl::where('group_id', $request->group_id)->get();

            // Loop melalui setiap entri pengajuan PKL dan atur pembimbing_id
            foreach ($pengajuanPkl as $pkl) {
                // Periksa apakah pembimbing_id_1 sudah terisi
                if ($pkl->pembimbing_id_1 === null) {
                    // Jika belum terisi, masukkan nilai baru ke pembimbing_id_1
                    $pkl->pembimbing_id_1 = $request->pembimbing_id;
                } elseif ($pkl->pembimbing_id_2 === null) {
                    // Jika pembimbing_id_1 sudah terisi, masukkan nilai baru ke pembimbing_id_2
                    $pkl->pembimbing_id_2 = $request->pembimbing_id;
                } else {
                    // Jika kedua kolom sudah terisi, kembalikan respons dengan pesan kesalahan
                    return response()->json(['error' => 'Kedua kolom pembimbing sudah terisi'], 400);
                }
                $pkl->save(); // Simpan perubahan
            }

            return response()->json(['message' => 'Pembimbing berhasil ditugaskan ke kelompok siswa']);
        } catch (\Exception $e) {
            // Tangkap kesalahan dan kirimkan respons JSON dengan kode status 500
            return response()->json(['error' => 'Gagal menugaskan pembimbing', 'message' => $e->getMessage()], 500);
        }
    }

    public function all()
    {
        try {
            // Ambil semua data siswa yang memiliki status 'Diterima' di tabel pengajuan PKL
            $acceptedUsers = PengajuanPKL::where('status', 'Diterima')
                ->join('users', 'pengajuan_pkl.user_id', '=', 'users.id')
                ->select('pengajuan_pkl.group_id', 'pengajuan_pkl.nama_perusahaan', 'users.id as user_id', 'users.name', 'users.kelas', 'users.nisn')
                ->distinct()
                ->get();

            // Transformasikan data yang telah diperoleh
            $transformedData = $acceptedUsers->map(function ($user) {
                return [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'kelas' => $user->kelas,
                    'nisn' => $user->nisn,
                    'group_id' => $user->group_id,
                    'nama_perusahaan' => $user->nama_perusahaan,
                ];
            });

            return response()->json($transformedData, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching accepted group data: ' . $e->getMessage()], 500);
        }
    }
    public function getDaftarSiswa(Request $request)
    {
        try {
            // Ambil data pengajuan PKL yang memiliki status 'Diterima' dan relasi pembimbing
            $pengajuan = PengajuanPkl::where('status', 'Diterima')
                ->with('pembimbing:id,name,nip,nomer_telpon,email')
                ->get();

            // Cek apakah data ditemukan
            if ($pengajuan->isEmpty()) {
                return response()->json(['error' => 'Data siswa dengan status Diterima tidak ditemukan'], 404);
            }

            return response()->json($pengajuan);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error mencari data siswa' . $e->getMessage()], 500);
        }
    }

    public function datasppd()
    {
        try {
            // Ambil semua data pembimbing dari tabel Pembimbing
            $pembimbings = Pembimbing::all();

            // Loop melalui setiap pembimbing dan ambil data yang diperlukan
            $dataPembimbings = $pembimbings->map(function ($pembimbing) {
                return [
                    'user_id' => $pembimbing->user_id,
                    'status' => $pembimbing->status,
                    'tanggal' => $pembimbing->tanggal,
                    'hari' => $pembimbing->hari,
                    'waktu' => $pembimbing->waktu,
                    'lamanya_perjalanan' => $pembimbing->lamanya_perjalanan,
                ];
            });

            return response()->json($dataPembimbings, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching pembimbing data.' . $e->getMessage()], 500);
        }
    }
}