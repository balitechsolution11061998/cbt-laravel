<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $classes = [];

        // Kelas 10-12 with 1 IPA class and 1 IPS class per level
        for ($i = 10; $i <= 12; $i++) {
            // Create 1 IPA class for each level
            $classes[] = [
                'name' => "Kelas $i IPA 1",
            ];

            // Create 1 IPS class for each level
            $classes[] = [
                'name' => "Kelas $i IPS 1",
            ];
        }

        Kelas::insert($classes);
    }

}
