<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    protected $table = 'perusahaan';
    protected $fillable = ['nama_perusahaan', 'alamat_perusahaan', 'email_perusahaan', 'siswa_dibutuhkan'];

    public function pengajuanPkl()
    {
        return $this->hasMany(PengajuanPkl::class);
    }
}
