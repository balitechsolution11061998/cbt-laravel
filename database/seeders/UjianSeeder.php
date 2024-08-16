<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;
use App\Models\Ujian;
use App\Models\PaketSoal;
use App\Models\Rombel;
use App\Models\MataPelajaran;
use Faker\Factory as Faker;

class UjianSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Fetch existing PaketSoal, Rombel, and MataPelajaran records
        $paketSoalIds = PaketSoal::pluck('id')->toArray();
        $kelasId = Kelas::pluck('id')->toArray();
        $mataPelajaranIds = MataPelajaran::pluck('id')->toArray();

        $ujianData = [];

        for ($i = 1; $i <= 1; $i++) {
            $ujianData[] = [
                'nama' => 'Ujian ' . $i,
                'paket_soal_id' => $faker->randomElement($paketSoalIds),
                'kelas_id' => $faker->randomElement($kelasId),
                'waktu_mulai' => $faker->dateTimeBetween('now'),
                'durasi' => $faker->numberBetween(30, 120), // in minutes
                'poin_benar' => $faker->numberBetween(1, 10),
                'poin_salah' => $faker->numberBetween(0, 5),
                'poin_tidak_jawab' => $faker->numberBetween(0, 5),
                'keterangan' => $faker->sentence,
                'kelas' => json_encode(['Kelas ' . $faker->randomElement(['A', 'B', 'C', 'D'])]),
                'tampilkan_nilai' => $faker->boolean,
                'tampilkan_hasil' => $faker->boolean,
                'gunakan_token' => $faker->boolean,
                'mata_pelajaran_id' => $faker->randomElement($mataPelajaranIds),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Ujian::insert($ujianData);
    }
}
