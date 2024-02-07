<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'absensi';
    protected $fillable = [
        'latitude',
        'longitude',
        'nama',
        'nisn',
        'foto',
        'tanggal_absen'
    ];

    public function pengajuanPKL()
    {
        return $this->belongsTo(PengajuanPKL::class, 'user_id','nisn');
        
    }

}
