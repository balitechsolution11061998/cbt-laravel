<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketSoal extends Model
{
    use HasFactory;
    protected $table = 'paket_soal';
    public $guarded = [];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kode_kelas', 'id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'kode_mata_pelajaran', 'id');
    }

    public function soals()
    {
        return $this->hasMany(Soal::class, 'paket_soal_id');
    }
}
