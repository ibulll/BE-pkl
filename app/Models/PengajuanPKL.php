<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPKL extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'cv',
        'portofolio',
        'email',
        'alamat',
        'file_cv',
        'file_portofolio',
    ];
}
