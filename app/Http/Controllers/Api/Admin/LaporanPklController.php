<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPKL;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanPklController extends Controller
{
    public function generatelaporan()
    {
        // Dapatkan data pengajuan PKL yang statusnya sudah diterima dari database bersama dengan informasi pengguna yang sesuai
        $pengajuanPKL = PengajuanPKL::with('user')->where('status', 'diterima')->get();

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf();
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Aktifkan untuk mengizinkan gambar yang di-host eksternal
        $dompdf->setOptions($options);
        $dompdf->loadHtml(view('laporan_pkl', compact('pengajuanPKL'))->render());
        
        // Atur ukuran dan orientasi halaman
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Keluarkan file PDF ke browser
        return $dompdf->stream('laporan_pkl.pdf');
    }
}
