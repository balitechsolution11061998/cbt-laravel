<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Rombel;

class RombelSeeder extends Seeder
{
    public function run()
    {
        $kelas = Kelas::all();
        $rombels = [];

        foreach ($kelas as $kls) {
            if (preg_match('/^Kelas (\d+)$/', $kls->name, $matches)) {
                $grade = (int)$matches[1];

                // Add Rombel entries for Bahasa, IPA, and IPS
                if ($grade >= 10 && $grade <= 10) {
                    $rombels[] = [
                        'kelas_id' => $kls->id,
                        'nama_rombel' => "Bahasa $grade"
                    ];
                    // $rombels[] = [
                    //     'kelas_id' => $kls->id,
                    //     'nama_rombel' => "IPA $grade"
                    // ];
                    // $rombels[] = [
                    //     'kelas_id' => $kls->id,
                    //     'nama_rombel' => "IPS $grade"
                    // ];
                }
            }
        }

        Rombel::insert($rombels);
    }
}
