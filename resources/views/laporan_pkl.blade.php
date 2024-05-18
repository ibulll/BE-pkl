<!-- resources/views/laporan_pkl.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Praktik Kerja Lapangan (PKL)</title>
</head>
<body>
    <h1>Laporan Praktik Kerja Lapangan (PKL)</h1>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>Kelas</th>
                <th>Nama Perusahaan</th>
                <th>Email Perusahaan</th>
                <th>Alamat Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuanPKL as $key => $pengajuanPKL)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $pengajuanPKL->user->name }}</td>
                <td>{{ $pengajuanPKL->user->nisn }}</td>
                <td>{{ $pengajuanPKL->user->kelas }}</td>
                <td>{{ $pengajuanPKL->nama_perusahaan }}</td>
                <td>{{ $pengajuanPKL->email_perusahaan }}</td>
                <td>{{ $pengajuanPKL->alamat_perusahaan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
