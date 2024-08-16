<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;
    protected $table = 'soal';
    protected $fillable = [
        'paket_soal_id',
        'jenis',
        'pertanyaan',
        'pertanyaan_a',
        'pertanyaan_b',
        'pertanyaan_c',
        'pertanyaan_d',
        'jawaban_benar',
    ];
    public function paketSoal()
    {
        return $this->hasOne(PaketSoal::class, 'id', 'paket_soal_id');
    }
    public function soalPilihan()
    {
        return $this->hasMany(SoalPilihan::class, 'soal_id');
    }
}
