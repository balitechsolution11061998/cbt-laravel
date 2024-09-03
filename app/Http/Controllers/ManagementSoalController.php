<?php

namespace App\Http\Controllers;

use App\Imports\SoalImport;
use App\Models\ManagementPaketSoal;
use App\Models\Soal;
use App\Models\SoalPilihan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManagementSoalController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('manajement-soal.index');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new SoalImport, $request->file('file'));

        return redirect()->back()->with('success', 'Soal imported successfully.');
    }



    public function store(Request $request)
{
    // Validate the request data
    $request->validate([
        'id' => 'nullable|exists:soal,id', // Validate ID for update
        'paket_soal_id' => 'required|exists:paket_soal,id',
        'jenis' => 'required|in:pilihan_ganda,essai',
        'pertanyaan' => 'nullable|string',
        'jawaban_essai' => 'nullable|string', // Validate for essay answer
        'pilihan_ganda_a' => 'nullable|string',
        'pilihan_ganda_b' => 'nullable|string',
        'pilihan_ganda_c' => 'nullable|string',
        'pilihan_ganda_d' => 'nullable|string',
        'jawaban_benar' => 'nullable|string', // For storing the correct answer for pilihan_ganda
    ]);

    // Start a database transaction
    \DB::transaction(function () use ($request) {
        // Determine the correct answer based on the type
        $jawabanBenar = $request->jenis === 'essai' ? $request->jawaban_essai : $request->jawaban_benar;

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
                'jawaban_benar' => $jawabanBenar,
            ]
        );

        // Handle pilihan_ganda type only
        if ($request->jenis === 'pilihan_ganda') {
            // Delete existing pilihan for this soal
            SoalPilihan::where('soal_id', $soal->id)->delete();

            // Insert new pilihan for pilihan_ganda
            SoalPilihan::create([
                'soal_id' => $soal->id,
                'jawaban' => $request->jawaban_benar,
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
                return $soal->paketSoal ? $soal->paketSoal->kode_paket : 'N/A'; // Adjust 'kode_paket' to the correct column name
            })
            ->addColumn('action', function ($soal) {
                return '<button class="btn btn-primary btn-sm" onclick="editSoal(' . $soal->id . ')"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteSoal(' . $soal->id . ')"><i class="fas fa-trash"></i> Delete</button>';
            })
            ->make(true);
    }
}
