<?php

namespace App\Imports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\ToModel;

class SoalImport implements ToModel
{
    public function model(array $row)
    {
        return new Soal([
            'paket_soal_id' => $row[0], // Assuming the first column in the Excel file is 'paket_soal_id'
            'jenis' => $row[1],          // Assuming the second column is 'jenis'
            'pertanyaan' => $row[2],     // Assuming the third column is 'pertanyaan'
            'pertanyaan_a' => $row[3],   // Assuming the fourth column is 'pertanyaan_a'
            'pertanyaan_b' => $row[4],   // Assuming the fifth column is 'pertanyaan_b'
            'pertanyaan_c' => $row[5],   // Assuming the sixth column is 'pertanyaan_c'
            'pertanyaan_d' => $row[6],   // Assuming the seventh column is 'pertanyaan_d'
            'jawaban_benar' => $row[7],  // Assuming the eighth column is 'jawaban_benar'
        ]);
    }
}
