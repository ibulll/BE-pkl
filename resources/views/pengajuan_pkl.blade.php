
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Surat Pengajuan Ijin Prakerin</title>
<style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #333;
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
    }
    p {
        margin-bottom: 5px;
        font-size: 14px; /* Mengurangi ukuran teks */
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
        display: flex; /* Menggunakan flexbox */
        align-items: center; /* Tengahkan item dalam flex container */
        justify-content: space-between; /* Tengahkan item secara horizontal */
        border-bottom: 6px solid #333; /* Tambahkan garis horizontal */
        padding-bottom: 10px; /* Berikan ruang di bawah garis */
    }
    .header img {
        width: 100px; /* Sesuaikan ukuran logo */
        margin-right: 20px; /* Berikan ruang antara logo dan teks */
    }
    .address {
        margin-bottom: 20px;
        text-align: left; /* Rata kiri teks pada bagian "Kepada Yth" */
    }
    .content {
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        border: 1px solid #333;
        padding: 8px;
        text-align: center;
    }
    .attachment {
        margin-top: 20px; /* Tambahkan ruang di atas lampiran */
    }
    .left-content {
        float: left;
        width: 50%;
    }
    .left-content p {
        text-align: left; /* Rata kiri teks pada bagian kiri */
    }
    .left-content p span {
        display: inline-block;
        width: 120px; /* Sesuaikan lebar agar sejajar */
    }
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="app/public/landing-page/assets/img/Logo-Jawa-Barat-1.png" alt="Logo SMK NEGERI 1 CIOMAS">
        <div>
            <p>PEMERINTAH DAERAH PROVINSI JAWA BARAT</p>
            <p>SMK NEGERI 1 CIOMAS</p>
            <p>NPSN: 20254135 NSS: 401020229101</p>
            <p>Jl Raya Laladon Desa Laladon Ciomas Telp: (0251) 7520933</p>
            <p>Email: smkn1 ciomas@yahoo.co.id</p>
            <p>BOGOR 16610</p>
        </div>
    </div>
    
    <h1>Surat Pengajuan Ijin Prakerin</h1>

    <div class="left-content">
        <p><span>Nomor</span>: 423.7/{{$bulanRomawi}}/{{$tahun}}/{{$nomorSurat}}-SMKNICIOMAS</p>
        <p><span>Lampiran</span>: 1</p>
        <p><span>Perihal</span>: Permohonan Ijin Prakerin (Praktek Kerja Industri)</p>
    </div>
    
    <div class="address">
        <p>Kepada Yth:</p>
        <p>{{ $namaPerusahaan }}</p>
    </div>
    
    <div class="content">
        <p>Assalamualaikum Warahmatullah Wabarakatuh</p>
        <p>Dengan Hormat,</p>
        <p>Teriring Salam dan Doa kepada Bapak dan Ibu, semoga senantiasa diberikan kesehatan dalam menjalankan semua aktifitasnya</p>
        <p>Sehubungan dengan program kegiatan belajar mengajar siswa/siswi SMK Negeri 1 Ciomas Kab. Bogor tahun ajaran {{$tahunAjar}} tentang penyelenggaraan praktek kerja industri (Prakerin), maka dengan ini kami mohon izin untuk diperkenankan siswa/siswa kami agar mengikuti prakerin di perusahaan yang bapak/ibu pimpin.</p>
        <p>Adapun waktu dan pelaksanaannya akan dimulai pada bulan {{$bulanTahun}}, lama pelaksanaan sekitar {{$pelaksanaan}} (terhitung dari hari pertama prakerin).</p>
        <p>Untuk informasi lebih lanjut hubungi {{$kontak}}</p>
        <p>Demikian permohonan ini kami sampaikan atas kerjasamanya kami ucapkan terimakasih.</p>
        <p>Wassalamualaikum Warahmatullah Wabarakatuh.</p>
        <p>Ciomas, {{ date('d F Y') }}</p>
        <p>Kepala Sekolah.</p>
    </div>
    
    <div class="signature">
        <p>SORTECO Des Matuli M.Pd ASPEND 196808051995041001</p>
    </div>
    
    <div class="attachment">
        <p>Lampiran:</p>
        <p>Berikut kami lampirkan biodata singkat siswa kami yang akan mengikuti prakerin di {{$namaPerusahaan}} yaitu:</p>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Portofolio</th>
                <th>CV</th>
            </tr>
            @foreach($dataList as $data)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data['nama'] }}</td>
                    <td>{{ $data['kelas'] }}</td>
                    <td><a href="{{ $data['file_portofolio'] }}" class="pdf-link">Lihat Portofolio</a></td>
                    <td><a href="{{ $data['file_cv'] }}" class="pdf-link">Lihat CV</a></td>
                </tr>
            @endforeach
        </table>
             
    </div>
</div>

</body>
</html>
