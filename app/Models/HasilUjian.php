<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjian extends Model
{
    use HasFactory;
    protected $table = 'hasil_ujian';

    protected $fillable = [
        'ujian_id',
        'jumlah_benar',
        'jumlah_salah',
        'nilai',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class, 'ujian_id');
    }
}
