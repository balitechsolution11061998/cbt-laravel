<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Soal;
use App\Models\SoalPilihan;
use App\Models\PaketSoal;
use Illuminate\Support\Facades\File; // To read the JSON file

class SoalSeeder extends Seeder
{
    public function run()
    {
        // Set Faker locale to Indonesian
        $faker = \Faker\Factory::create('id_ID');

        // Path to the JSON file
        $filePath = public_path('json/soalbahasaindonesia.json');

        // Ensure the file exists
        if (!File::exists($filePath)) {
            throw new \Exception("File not found at path: $filePath");
        }

        // Read and decode the JSON file
        $jsonContent = File::get($filePath);
        $questions = json_decode($jsonContent, true);

        // Get all PaketSoal IDs
        $paketSoalIds = PaketSoal::pluck('id')->toArray();

        // Number of questions to be inserted per PaketSoal
        $numberOfQuestionsPerPaket = 50;

        foreach ($paketSoalIds as $paketSoalId) {
            // Randomly select questions to ensure variety
            $selectedQuestions = array_rand($questions, min($numberOfQuestionsPerPaket, count($questions)));

            // Ensure we are working with an array of questions
            if (!is_array($selectedQuestions)) {
                $selectedQuestions = [$selectedQuestions];
            }

            foreach ($selectedQuestions as $index) {
                $question = $questions[$index];

                // Create Soal record
                $soal = Soal::create([
                    'paket_soal_id' => $paketSoalId,
                    'jenis' => 'pilihan_ganda', // Set to 'pilihan_ganda'
                    'pertanyaan' => $question['question'], // The question text
                    'pertanyaan_a' => $question['options'][0], // Option A
                    'pertanyaan_b' => $question['options'][1], // Option B
                    'pertanyaan_c' => $question['options'][2], // Option C
                    'pertanyaan_d' => $question['options'][3], // Option D
                    'media' => $faker->imageUrl(), // Placeholder image URL
                    'ulang_media' => $faker->imageUrl(), // Placeholder image URL
                    'jawaban_benar' => $question['correct_option'], // The correct answer
                ]);

                // Insert SoalPilihan data for each question
                foreach ($question['options'] as $index => $option) {
                    SoalPilihan::create([
                        'soal_id' => $soal->id,
                        'jawaban' => $option,
                        'media' => $faker->imageUrl(), // Placeholder image URL
                    ]);
                }
            }
        }
    }
}
