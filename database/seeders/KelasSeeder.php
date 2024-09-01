<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;

class KelasSeeder extends Seeder
{
    public function run()
    {
        $classes = [];

        // Kelas 10-12 with 7 IPA classes and 3 IPS classes
        for ($i = 10; $i <= 12; $i++) {
            // Create 7 IPA classes for each level
            for ($j = 1; $j <= 7; $j++) {
                $classes[] = [
                    'name' => "Kelas $i IPA $j",
                ];
            }
            // Create 3 IPS classes for each level
            for ($j = 1; $j <= 3; $j++) {
                $classes[] = [
                    'name' => "Kelas $i IPS $j",
                ];
            }
        }

        Kelas::insert($classes);
    }
}
