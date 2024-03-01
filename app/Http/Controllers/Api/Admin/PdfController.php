<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function generatePDF(Request $request)
    {
        // Lakukan validasi data jika diperlukan

        $nomorSurat = $request->input('nomor_surat');
        $tahunAjar = $request->input('tahun_ajar');
        $bulanTahun = $request->input('bulan_tahun');
        $pelaksanaan = $request->input('lama_pelaksanaan');
        $kontak = $request->input('kontak');

        // Ambil semua data pengajuan PKL yang sesuai dengan 'user_id' dari permintaan
        $pengajuanPklList = PengajuanPkl::with('user')->where('group_id', $request->input('group_id'))->get();

        // Inisialisasi array untuk menyimpan data setiap pengajuan PKL
        $dataList = [];

        // Inisialisasi variabel untuk menyimpan nama perusahaan
        $namaPerusahaan = '';

        // Inisialisasi variabel untuk menyimpan bulan romawi
        $bulanRomawi = '';

        // Loop melalui setiap pengajuan PKL untuk mengambil informasi yang diperlukan
        foreach ($pengajuanPklList as $pengajuanPkl) {
            // Dapatkan nomor bulan dari waktu pembuatan data
            $bulan = date('n', strtotime($pengajuanPkl->created_at));

            // Konversi nomor bulan ke format romawi
            $bulanRomawi = $this->convertToRoman($bulan);

            // Tentukan URL untuk file CV dan portofolio
            $cvUrl = $pengajuanPkl->file_cv ? asset('storage/' . $pengajuanPkl->file_cv) : $pengajuanPkl->cv;
            $portofolioUrl = $pengajuanPkl->file_portofolio ? asset('storage/' . $pengajuanPkl->file_portofolio) : $pengajuanPkl->portofolio;

            $dataList[] = [
                'nama' => $pengajuanPkl->user->name,
                'nisn' => $pengajuanPkl->user->nisn,
                'kelas' => $pengajuanPkl->user->kelas,
                'bulan_romawi' => $bulanRomawi,
                'nama_perusahaan' => $pengajuanPkl->nama_perusahaan,
                'file_cv' => $cvUrl,
                'file_portofolio' => $portofolioUrl,
                // Tambahkan data lain yang diperlukan
            ];

            // Simpan nama perusahaan dari pengajuan PKL pertama
            if (!$namaPerusahaan) {
                $namaPerusahaan = $pengajuanPkl->nama_perusahaan;
            }
        }

        $tahun = date('Y');

        // Load view PDF dengan data yang telah ditentukan
        $pdf = PDF::loadView('pengajuan_pkl', compact('dataList', 'namaPerusahaan', 'bulanRomawi', 'tahun', 'nomorSurat', 'tahunAjar', 'bulanTahun', 'pelaksanaan', 'kontak'));

        // Set option untuk membuat hyperlink aktif
        $pdf->getDomPDF()->set_option('enable_php', true);
        $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);

        // Kembalikan file PDF sebagai respons
        return $pdf->download('surat_pengajuan_pkl.pdf');
    }

    // Fungsi untuk mengonversi nomor ke format romawi
    private function convertToRoman($number)
    {
        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];

        $result = '';

        foreach ($map as $roman => $value) {
            $matches = intval($number / $value);
            $result .= str_repeat($roman, $matches);
            $number %= $value;
        }

        return $result;
    }
}
