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
        'cv',
        'portofolio',
        'email',
        'alamat',
        'file_cv',
        'file_portofolio',
        'status'
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }
}
