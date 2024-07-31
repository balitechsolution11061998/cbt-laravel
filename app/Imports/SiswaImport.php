<?php
// app/Imports/SiswaImport.php
namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Siswa([
            'rombel_id' => $row['rombel_id'],
            'nama' => $row['nama'],
            'nis' => $row['nis'],
            'jenis_kelamin' => $row['jenis_kelamin'],
        ]);
    }
}
