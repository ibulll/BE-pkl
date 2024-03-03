<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 15cm;
            padding: 0.5cm; /* Mengurangi padding dari 2cm menjadi 0.5cm */
            margin: 0 auto;
            border: 1px solid #d3d3d3;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px 0; /* Mengurangi padding dari 10px menjadi 5px */
            text-align: center;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        .content {
            margin-top: 10px; /* Mengurangi margin dari 20px menjadi 10px */
        }

        hr {
            border: none;
            border-bottom: 2px solid black;
        }

        .center-text {
            text-align: center;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        .label {
            width: 250px;
            display: inline-block;
            text-align: left;
            margin-left: 100px;
        }

        .value {
            display: inline-block;
            margin: 0;
            vertical-align: top;
            max-width: calc(100% - 420px);
        }

        .signature {
            text-align: right;
            margin-top: 2%; /* Mengurangi margin dari 5% menjadi 2% */
        }

        .signature p {
            margin-left: 55%; /* Mengurangi margin dari 60% menjadi 55% */
            text-align: left;
        }

        .ttd {
            margin-top: 10%; /* Mengurangi margin dari 15% menjadi 10% */
        }

        /* Menambahkan gaya untuk halaman berikutnya */
        .page-break {
            page-break-before: always;
        }

        /* Gaya tambahan untuk halaman berikutnya */
        .page {
            padding: 0.5cm;
            margin: 0 auto;
            border: 1px solid #d3d3d3;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="navbar">
            <div class="logo">
            <img src="../public/photos/jawabarat.png" alt="Logo SMK NEGERI 1 CIOMAS">
            </div>
            <div>
                <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
                <h3>SMK NEGERI 1 CIOMAS</h3>
                <p>NPSN: 20254135 NSS: 401020229101</p>
                <p>Jl Raya Laladon Desa Laladon Ciomas</p>
                <p>Telp: (0251) 7520933, web: www.smknlciomas.sch.id</p>
                <p>Email: smkn1 ciomas@yahoo.com</p>
                <p>BOGOR-16610</p>
            </div>
        </div>
        <div class="center-text">
            <hr>
            <h2>SURAT TUGAS</h2>
            <p>Nomor: 424.1/1/2024/{{$nosurat}}-SMKN1CIOMAS</p>
        </div>
        <div class="content clearfix">
            <p>Yang bertanda tangan dibawah ini Kepala SMK Negeri 1 Ciomas Kabupaten Bogor memberikan tugas kepada:</p>
            <div class="clearfix">
                <p>
                    <span class="label">Nama</span>
                    <span class="value">: {{$name}}</span>
                </p>
                <p>
                    <span class="label">NIP / NUPTK</span>
                    <span class="value">: {{$nip}}</span>
                </p>
                <p>
                    <span class="label">Pangkat, Gol/ Ruang</span>
                    <span class="value">: {{$pangkat}}</span>
                </p>
                <p>
                    <span class="label">Jabatan</span>
                    <span class="value">: {{$jabatan}}</span>
                </p>
                <p>
                    <span class="label">Unit Kerja</span>
                    <span class="value">: SMK Negeri 1 Ciomas</span>
                </p>
                <p>Untuk {{$status}} Siswa Prakerin, yang akan dilaksanakan pada:</p>
                <p>
                    <span class="label">Hari</span>
                    <span class="value">: {{$hari}}</span>
                </p>
                <p>
                    <span class="label">Tanggal</span>
                    <span class="value">: {{$tanggal}}</span>
                </p>
                <p>
                    <span class="label">Waktu</span>
                    <span class="value">: {{$waktu}}</span>
                </p>
                <p>
                    <span class="label">Tempat</span>
                    <span class="value">: {{$nama_perusahaan}}</span>
                </p>
            </div>
            <p>Demikian surat tugas ini dibuat, untuk dapat dilaksanakan sebaik-baiknya.</p>
            <div class="signature">
                <p>Ciomas, {{ date('d F Y') }}</p>
                <p>Kepala Sekolah,</p>
                <div class="ttd">
                    <p>Drs. Mahdi, M.Pd.</p>
                    <p>NIP 196808051995041001</p>
                </div>
            </div>
        </div>
    </div>

        <!-- Awal halaman berikutnya -->
        <div class="page-break"></div>

        <div class="page">
            <p>
                PEMERINTAH DAERAH PROVINSI JAWA BARAT NPSN: 20254135 NSS:
                401020229101
            </p>
            <p>SMK NEGERI 1 CIOMAS</p>
            <p>Jl Raya Laladon Desa Laladon Ciomas</p>
            <p>Email: smkn1 ciomas@yahoo.com</p>
            <p>BOGOR 16610</p>
            <p>Lembar Ke</p>
            <p>Kode No Nomor</p>
            <p>SPPD: 424.1/1/2024/0573-SMKNICIOMAS</p>
            <p>SURAT PERINTAH PERJALANAN DINAS</p>
            <p>
                Pejabat yang memberi perintah Nama/NIP pegawai yang
                diperintahkan
            </p>
            <p>1.Kepala Sekolah</p>
            <p>{{$name}}</p>
            <p>{{$nip}}</p>
            <p>Pangkat dan Golongan gaji menurut 1.- PP No. 6 Tahun 1997</p>
            <p>Jabatan/Intans</p>
            <p>Maksud Perjalanan Dinas Alat Angkutan yang Dipergunakan</p>
            <p>a. Tempat Berangkat</p>
            <p>b. Tempat Tujuan</p>
            <p>2. Guru</p>
            <p>Monitoring Siswa Prakerin</p>
            <p>Kendaraan Pribadi</p>
            <p>a. SMK Negeri 1 Ciomas</p>
            <p>b. BALAI PENGUJIAN MUTU DAN</p>
            <p>SERTIFIKASI PRODUK HEWAN</p>
            <p>a. LamanyaPerjalanan Dinas</p>
            <p>a. Hari 7</p>
            <p>b. Tanggal Berangkat b. 9 Januari 2024</p>
            <p>c. Tanggal harus Kembali</p>
            <p>Pendamping:</p>
            <p>Pembebanan Anggaran</p>
            <p>a. Intansi</p>
            <p>b. Kode Rekening</p>
            <p>Keterangan Lain-lain</p>
            <p>c. 9 Januari 2024</p>
            <p>Pangkat/Golongan.</p>
            <p>a.</p>
            <p>b.-</p>
            <p>T</p>
            <p>Jabatan:</p>
            <p>PEMERINTANA</p>
            <p>Dikeluarkan Tanggal di: Ciomas</p>
            <p>9 Januari 2024</p>
            <p>Kepala SMK Negeri 1 Ciomas</p>
            <p>GER Drs. Mahdi M.Pd. DINAS</p>
            <p>196808051995041001</p>
        </div>

        <div class="page">
            <p>
                II. Tiba di : BALAI PENGUJIAN MUTU DAN SERTIFIKASI PRODUK HEWAN
            </p>
            <p>Pada Tanggal: 9 Januari 2024</p>
            <p>Kepala</p>
            <p>NIP</p>
            <p>III. Tiba di Pada Tanggal : Kepala</p>
            <p>NIP.</p>
            <p>IV. Tiba di Pada Tanggal Kepala</p>
            <p>NIP.</p>
            <p>V. Tiba di Pada Tanggal Kepala</p>
            <p>NIP.</p>
            <p>
                VI. Tiba di : SMK Negeri 1 Ciomas Pada Tanggal : 9 Januari 2024
                Pejabat yang berwenang / Pejabat yang
            </p>
            <p>Ditunjuk</p>
            <p>Kepala SMK Negeri 1 Ciomas</p>
            <p>Drs. Malidi, M.Pd.</p>
            <p>CIOINED: 196808651995041001</p>
            <p>Catan Lain-lain</p>
            <p>
                Berangkat dari : BALAI PENGUJIAN MUTU DAN SERTIFIKASI PRODUK
                HEWAN
            </p>
            <p>Ke : SMK Negeri | Ciomas Pada Tanggal : 9 Januari 2024 Kepala</p>
            <p>NIP</p>
            <p>Berangkat dari:</p>
            <p>Ke Pada Tanggal Kepala</p>
            <p>NIP</p>
            <p>Berangkat dari Ke Pada Tanggal Kepala</p>
            <p>NIP.</p>
            <p>Berangkat dari Ke Pada Tanggal</p>
            <p>Kepala</p>
            <p>NIP.</p>
            <p>
                Telah diperiksa dengan keterangan bahwa perjalanan tersebut atas
                perintahnya dan semata-mata untuk kepentingan jabatan Pejabat
                Yang Berwenang / Pejabat Lamnya yang ditunjuk
            </p>
            <p>Kepala SMK Negeri 1 Ciomas</p>
            <p>Drs. Mahdi M.Pd.</p>
            <p>NIP: 196808051993 5041001</p>
            <p>HEND</p>
            <p>
                PERHATIAN: Pejabat yang berwenang menerbitkan SPPD, Pegawai yang
                melakukan perjalanan dinas, para pejabat yang mengesahkan
                tanggal berangkat/tiba, serta pemegang kas bertanggung jawab
                berdasarkan peraturan- peraturan Keuangan Negara apabila negara
                menderita rugi akibat kesalahan, kelalaian dan kealpaannya
            </p>
        </div>
    </body>
</html>
