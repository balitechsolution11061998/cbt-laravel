<?php

namespace App\Http\Controllers;

use App\Models\ManagementPaketSoal;
use App\Models\Soal;
use App\Models\SoalPilihan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManagementSoalController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('manajement-soal.index');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'id' => 'nullable|exists:soal,id', // Validate ID for update
            'paket_soal_id' => 'required|exists:paket_soal,id',
            'jenis' => 'required|in:pilihan_ganda,essai',
            'pertanyaan' => 'nullable|string',
            'pertanyaan_a' => 'nullable|string',
            'pertanyaan_b' => 'nullable|string',
            'pertanyaan_c' => 'nullable|string',
            'pertanyaan_d' => 'nullable|string',
            'media' => 'nullable|string',
            'ulang_media' => 'nullable|string',
            'jawaban_benar' => 'nullable|string', // For storing the correct answer for pilihan_ganda
            'pilihan' => 'nullable|array', // For pilihan_ganda, ensure it's an array if provided
            'pilihan.*.jawaban' => 'required_with:pilihan|string', // Ensure jawaban is a string if pilihan is provided
            'pilihan.*.pilihan' => 'required_with:pilihan|string', // Ensure pilihan is a string if pilihan is provided
            'pilihan.*.media' => 'nullable|string' // Ensure media is a string if provided
        ]);

        // Start a database transaction
        \DB::transaction(function () use ($request) {
            // Create or update the Soal
            $soal = Soal::updateOrCreate(
                ['id' => $request->id],
                [
                    'paket_soal_id' => $request->paket_soal_id,
                    'jenis' => $request->jenis,
                    'pertanyaan' => $request->pertanyaan,
                    'pertanyaan_a' => $request->pilihan_ganda_a,
                    'pertanyaan_b' => $request->pilihan_ganda_b,
                    'pertanyaan_c' => $request->pilihan_ganda_c,
                    'pertanyaan_d' => $request->pilihan_ganda_d,
                    'media' => $request->media,
                    'ulang_media' => $request->ulang_media,
                    'jawaban_benar' => $request->jawaban_benar,
                ]
            );

            // Handle pilihan_ganda type only
            if ($request->jenis === 'pilihan_ganda') {
                // Delete existing pilihan for this soal
                SoalPilihan::where('soal_id', $soal->id)->delete();

                // Insert new pilihan

                SoalPilihan::create([
                    'soal_id' => $soal->id,
                    'jawaban' => $request->jawaban_benar,
                    'media' => $request->media ?? null,
                ]);
            }
        });

        return response()->json(['success' => true]);
    }



    public function edit($id)
    {
        // Find the 'Soal' record by its ID
        $soal = Soal::with('paketSoal')->findOrFail($id);

        // Return the record as a JSON response
        return response()->json([
            'success' => true,
            'data' => $soal
        ]);
    }

    public function destroy($id)
    {
        Soal::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function data()
    {
        $soals = Soal::with('paketSoal')->get();

        return DataTables::of($soals)
            ->addIndexColumn()
            ->addColumn('paket_soal', function ($soal) {
                return $soal->paketSoal ? $soal->paketSoal->nama_paket_soal : 'N/A'; // Adjust 'nama_paket_soal' to the correct column name
            })
            ->addColumn('action', function ($soal) {
                return '<button class="btn btn-primary btn-sm" onclick="editSoal(' . $soal->id . ')"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteSoal(' . $soal->id . ')"><i class="fas fa-trash"></i> Delete</button>';
            })
            ->make(true);
    }
}
