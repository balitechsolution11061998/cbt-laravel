<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $fillable = ['kelas_id', 'nama', 'nis', 'jenis_kelamin'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function users()
    {
        return $this->hasOne(User::class, 'username', 'nis');
    }
}
