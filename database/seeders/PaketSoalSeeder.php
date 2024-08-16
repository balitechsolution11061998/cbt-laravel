<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaketSoal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Faker\Factory as Faker;

class PaketSoalSeeder extends Seeder
{
    public function run()
    {
        // Set Faker locale to Indonesian
        $faker = Faker::create('id_ID');

        // Fetch existing Kelas and MataPelajaran records
        $kelasIds = Kelas::pluck('id')->toArray();

        // Fetch the ID of the MataPelajaran record for Bahasa Indonesia
        $bahasaIndonesiaId = MataPelajaran::where('nama', 'Bahasa Indonesia')->pluck('id')->first();

        // Continuous description
        $description = implode(' ', [
            'Ini adalah keterangan untuk paket soal.',
            'Paket soal ini terdiri dari beberapa soal pilihan ganda.',
            'Silakan kerjakan soal dengan teliti dan tepat.',
            'Paket soal ini digunakan untuk ujian akhir semester.',
            'Harap selesaikan semua soal dalam paket ini.',
            'Jangan lupa memeriksa kembali jawaban Anda.',
            'Paket soal ini dibuat untuk menguji pengetahuan Anda.',
            'Kerjakan soal-soal ini dengan sungguh-sungguh.',
            'Paket soal ini mencakup berbagai topik pembelajaran.',
            'Semoga berhasil dalam mengerjakan paket soal ini.'
        ]);

        $paketSoal = [];

        // Generate 40 packages for each class
        foreach ($kelasIds as $kelasId) {
            for ($i = 1; $i <= 1; $i++) {
                $paketSoal[] = [
                    'kode_kelas' => $kelasId,
                    'kode_mata_pelajaran' => $bahasaIndonesiaId,
                    'kode_paket' => 'PAKET' . $kelasId . '-' . $i,
                    'keterangan' => $description, // Continuous description
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        PaketSoal::insert($paketSoal);
    }
}
