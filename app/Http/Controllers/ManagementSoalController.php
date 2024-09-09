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
        // Debugging purpose

        // Validation rules
        $rules = [
            'paket_soal_id' => 'required|integer|exists:paket_soal,id',
            'jenis' => 'required|in:pilihan_ganda,gambar',
            'pertanyaan_pg' => 'nullable|string', // Updated field name
            'pertanyaan_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pilihan_ganda_a' => 'nullable|string',
            'pilihan_ganda_b' => 'nullable|string',
            'pilihan_ganda_c' => 'nullable|string',
            'pilihan_ganda_d' => 'nullable|string',
            'jawaban_benar' => 'nullable|in:a,b,c,d',
        ];

        // Validate the request
        $request->validate($rules);

        // Process the form data
        $data = $request->except(['id']);

        // Map pertanyaan_pg to pertanyaan if applicable
        if ($request->jenis == 'pilihan_ganda') {
            $data['pertanyaan'] = $request->input('pertanyaan_pg');
        }

        // Handle file upload
        if ($request->hasFile('pertanyaan_image')) {
            $image = $request->file('pertanyaan_image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['pertanyaan_image'] = 'images/'.$imageName;
        }

        // Handle storing or updating data
        if ($request->id) {
            $soal = Soal::find($request->id);

            if ($soal) {
                $soal->update($data);
            } else {
                return response()->json(['error' => 'Soal not found'], 404);
            }
        } else {
            Soal::create($data);
        }

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
