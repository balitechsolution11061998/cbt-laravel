<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    use HasFactory;
    protected $table = 'ujian';
    protected $fillable = [
        'nama',
        'paket_soal_id',
        'kelas_id',
        'waktu_mulai',
        'durasi',
        'poin_benar',
        'poin_salah',
        'poin_tidak_jawab',
        'keterangan',
        'kelas',
        'tampilkan_nilai',
        'tampilkan_hasil',
        'gunakan_token',
        'mata_pelajaran_id'
    ];

    public function paketSoal()
    {
        return $this->belongsTo(PaketSoal::class, 'paket_soal_id');
    }


    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }



    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function hasilUjian()
    {
        return $this->hasMany(HasilUjian::class, 'ujian_id');
    }
}
