<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JamKerjaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $jamKerjaId = $this->route('id');

        return [
            'kodeJamKerja' => [
                'required',
                'string',
                'max:255',
                'unique:jam_kerja,kode_jk,' . $jamKerjaId // Ensure uniqueness except for the current record
            ],
            'namaJamKerja' => 'required|string|max:255',
            'awalJamMasuk' => 'required|date_format:H:i:s',
            'jamMasuk' => 'required|date_format:H:i:s|after:awalJamMasuk',
            'akhirJamMasuk' => 'required|date_format:H:i:s|after:jamMasuk',
            'jamPulang' => 'required|date_format:H:i:s',
            'lintasHari' => 'required|boolean'
        ];
    }

    public function messages()
    {
        return [
            'kodeJamKerja.required' => 'Kode Jam Kerja harus diisi',
            'namaJamKerja.required' => 'Nama Jam Kerja harus diisi',
            'awalJamMasuk.required' => 'Awal Jam Masuk harus diisi',
            'jamMasuk.required' => 'Jam Masuk harus diisi',
            'jamMasuk.after' => 'Jam Masuk harus lebih dari Awal Jam Masuk',
            'akhirJamMasuk.required' => 'Akhir Jam Masuk harus diisi',
            'akhirJamMasuk.after' => 'Akhir Jam Masuk harus lebih dari Jam Masuk',
            'jamPulang.required' => 'Jam Pulang harus diisi',
            'lintasHari.required' => 'Lintas Hari harus diisi',
        ];
    }
}

