<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'jurnal';
    protected $fillable = ['user_id', 'kegiatan', 'status', 'waktu', 'tanggal'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pengajuanPKL()
    {
        return $this->belongsTo(PengajuanPKL::class, 'nisn', 'nisn');
    }

}
