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
        'cv',
        'portofolio',
        'nama_perusahaan',
        'email_perusahaan',
        'alamat_perusahaan',
        'file_cv',
        'file_portofolio',
        'group_id',
        'pembimbing_id_1',
        'pembimbing_id_2',
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

    public function pengajuanPkl()
    {
        return $this->hasMany(PengajuanPkl::class, 'user_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(User::class, 'pembimbing_id_1', 'pembimbing_id_2');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
