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
            padding: 0;
            margin: 0;
        }

        .container {
            max-width: 800px;
            line-height: normal;
            /* margin: 20px auto;
            padding: 20px; */
            /* border: 1px solid #333; */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .header {
            text-align: center;
            /* width: 300px; */
            margin: 0px;
            /* Mengatur jarak antara baris teks menjadi minimum */
            /* margin-bottom: 20px; */
            display: flex;
            /* align-items: center; */
            /* justify-content: space-between; */
            border-bottom: 6px solid #333;
            padding-bottom: 10px;
        }

        .text-header {
            line-height: normal;
            margin: 0;
            /* Menghilangkan margin */
        }


        .header img {
            width: 100px;
            margin-right: 80%;
            margin-top: 80px;
            margin-left: 0;
            /* Menghapus margin-left dan mengubah margin-right */
            position: absolute;
        }


        .address {
            margin-bottom: 20px;
            text-align: left;
            padding-left: 100px;
            line-height: 1.2;
            /* display:flex; */
            font-weight: bold;
        }

        .content {
            /* margin-top: 90px; */
            text-align: justify;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }

        .attachment {
            margin-top: 20px;
        }

        .left-content {
            float: left;
            display: inline-block;
            width: 50%;
        }

        .left-content p {
            text-align: left;
        }

        .left-content p span {
            display: inline-block;
            width: 120px;

        }

        .text {
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .text-2 {
            text-align: right;
            margin-top: 5px;
        }

        .blue-text {
            color: blue;
            /* Warna teks biru */
            text-decoration: underline;
            /* Garis bawah pada teks */
        }

        .signature {
            clear: both;
            text-align: right;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .email {
            margin-right: 5px;
        }

        .judul-1 {
            font-size: 120%;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <div class="gambar-header">
                <img src="../public/photos/jawabarat.png" alt="Logo SMK NEGERI 1 CIOMAS">
            </div>
            <div class="text-header">
                <p class="judul-1"><b>PEMERINTAH DAERAH PROVINSI JAWA BARAT</b></p>
                <p>
                    <font size="5"><b>SMK NEGERI 1 CIOMAS</b></font>
                </p>
                <p>
                    <font size="1"><b>NPSN: 20254135 NSS: 401020229101</b></font>
                </p>
                <p>Jl Raya Laladon Desa Laladon Ciomas Telp: (0251) 7520933</p>
                <p><span class="email">Email:</span><span class="blue-text">smkn1ciomas@yahoo.co.id</span></p>
                <p class="blue-text">BOGOR-16610</p>
            </div>
        </div>

        <div class="left-content">
            <p><span>Nomor</span>: 423.7/{{$bulanRomawi}}/{{$tahun}}/{{$nomorSurat}}-SMKNICIOMAS</p>
            <p><span>Lampiran</span>: 1</p>
            <p><b><span>Perihal</span>:
                    Permohonan Ijin Prakerin </br>
                    (Praktek Kerja Industri)</b>
            </p>
        </div>

        <div class="address">
            <p>Kepada Yth:</p>
            <p>Pimpinan {{ $namaPerusahaan }}</p>
            <p>di</p>
            <p>Tempat</p>
        </div>

        <div class="content">
            <p><i>Assalamualaikum Warahmatullah Wabarakatuh</i></p>
            <p>Dengan Hormat,</p>
            <p>Teriring Salam dan Doa kepada Bapak dan Ibu, semoga senantiasa diberikan kesehatan dalam menjalankan
                semua aktifitasnya</p>
            <p>Sehubungan dengan program kegiatan belajar mengajar siswa/siswi SMK Negeri 1 Ciomas Kab. Bogor tahun
                ajaran {{$tahunAjar}} tentang penyelenggaraan praktek kerja industri (Prakerin), maka dengan ini kami
                mohon izin untuk diperkenankan siswa/siswa kami agar mengikuti prakerin di perusahaan yang bapak/ibu
                pimpin.</p>
            <p>Adapun waktu dan pelaksanaannya akan dimulai pada bulan {{$bulanTahun}}, lama pelaksanaan sekitar
                {{$pelaksanaan}} (terhitung dari hari pertama prakerin).</p>
            <p>Untuk informasi lebih lanjut hubungi {{$kontak}}</p>
            <p>Demikian permohonan ini kami sampaikan atas kerjasamanya kami ucapkan terimakasih.</p>
            </br>
            <p><i>Wassalamualaikum Warahmatullah Wabarakatuh.<i></p>
        </div>

        <div class="signature">
            <p>Ciomas, {{ date('d F Y') }}</p>
            <p>Kepala Sekolah.</p>
            <br />
            <br />
            <br />
            <div class="text">Drs Mahdi M.Pd</div>
            <div class="text-2">NIP 196808051995041001</div>
        </div>
        </br>
        </br>
        </br>
        </br>
        </br>
        <div class="attachment">
            <p>Lampiran:</p>
            <p>Berikut kami lampirkan biodata singkat siswa kami yang akan mengikuti prakerin di {{$namaPerusahaan}}
                yaitu:</p>
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
                    <td><a href="{{ $data['file_portofolio'] }}" class="pdf-link">Portofolio</a></td>
                    <td><a href="{{ $data['file_cv'] }}" class="pdf-link">CV</a></td>
                </tr>
                @endforeach
            </table>

        </div>
    </div>

</body>

</html>