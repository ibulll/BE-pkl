<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanPKL extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_pkl';

    protected $fillable = [
        'user_id',
        'nama',
        'nisn',
        'kelas',
        'cv',
        'portofolio',
        'nama_perusahaan',
        'email_perusahaan',
        'alamat_perusahaan',
        'file_cv',
        'file_portofolio',
        'group_id',
        'perusahaan_id',
        'status'
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }

}