<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalPilihan extends Model
{
    use HasFactory;
    protected $table = 'soal_pilihan';

    protected $fillable = [
        'soal_id',
        'jawaban',
        'status',
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class, 'soal_id');
    }
}
