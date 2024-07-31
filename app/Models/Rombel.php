<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;
    protected $fillable = ['kelas_id', 'nama_rombel'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }
}
