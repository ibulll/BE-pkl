<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    use HasFactory;
    protected $table = 'pembimbing';

    protected $fillable = ['nip', 'user_id', 'nomor_telpon', 'email'];

    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPKL::class, 'group_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userEmail()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}