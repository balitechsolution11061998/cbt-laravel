<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $classes = [];

        // Kelas 10-12
        for ($i = 10; $i <= 10; $i++) {
            $classes[] = [
                'name' => "Kelas $i",
                'description' => "Kelas $i",
            ];
        }

        Kelas::insert($classes);
    }
}
