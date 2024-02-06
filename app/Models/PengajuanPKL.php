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
        'nama',
        'nisn',
        'cv',
        'portofolio',
        'email',
        'alamat',
        'file_cv',
        'file_portofolio',
    ];

    public function jurnals()
{
    return $this->hasMany(Jurnal::class, 'nisn', 'nisn');
}

    public function getCvUrlAttribute()
    {
        return $this->file_cv ? Storage::url($this->file_cv) : null;
    }
    
    public function getPortofolioUrlAttribute()
    {
        return $this->file_portofolio ? Storage::url($this->file_portofolio) : null;
    }
    
}
