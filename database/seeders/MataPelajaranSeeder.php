<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            ['nama' => 'Bahasa Indonesia'],
        ];

        MataPelajaran::insert($subjects);
    }
}
