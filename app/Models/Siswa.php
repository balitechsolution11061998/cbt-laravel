<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $fillable = ['rombel_id', 'nama', 'nis', 'jenis_kelamin'];

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }

    public function users()
    {
        return $this->hasOne(User::class, 'nik', 'nis');
    }
}
