<?php

namespace App\Http\Controllers;

use App\Models\PaketSoal;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PaketSoalController extends Controller
{
    public function index()
    {
        return view('paket_soal.index');
    }

    public function data(Request $request)
    {
        $data = PaketSoal::with(['kelas', 'mataPelajaran'])->latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('kelas', function ($row) {
                return $row->kelas ? $row->kelas->name : 'N/A';
            })
            ->addColumn('mata_pelajaran', function ($row) {
                return $row->mataPelajaran ? $row->mataPelajaran->nama : 'N/A';
            })
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm editPaketSoal" title="Edit"><i class="fas fa-edit"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm deletePaketSoal" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function dataoptions()
    {
        // Fetch all classes
        $paketSoal = PaketSoal::select('id', 'kode_paket')->get();

        // Return the classes as JSON
        return response()->json($paketSoal);
    }

    public function store(Request $request)
    {
        // Define validation rules
        $rules = [
            'kode_kelas' => 'required|integer',
            'kode_paket' => 'required|unique:paket_soal,kode_paket,' . $request->id,
            'kode_mata_pelajaran' => 'required|integer',
            'keterangan' => 'nullable|string|max:255',
        ];

        // Validate the request
        $validatedData = $request->validate($rules);

        // Perform the update or create operation
        PaketSoal::updateOrCreate(['id' => $request->id], $validatedData);

        // Return a success response
        return response()->json(['success' => 'Paket Soal saved successfully.']);
    }


    public function update(Request $request, $id)
    {
        // Find the PaketSoal record by ID
        $paketSoal = PaketSoal::findOrFail($id);

        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'kode_kelas' => 'required|exists:kelas,id',
            'kode_mata_pelajaran' => 'required|max:255',
            'kode_paket' => [
                'required',
                'max:255',
                Rule::unique('paket_soal')->ignore($paketSoal->id),
            ],
            'keterangan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update the PaketSoal record
        $paketSoal->update($request->all());

        // Return a success response
        return response()->json(['success' => 'Paket Soal updated successfully']);
    }


    public function edit($id)
    {
        $paketSoal = PaketSoal::find($id);
        return response()->json($paketSoal);
    }

    public function destroy($id)
    {
        PaketSoal::find($id)->delete();
        return response()->json(['success' => 'Paket Soal deleted successfully.']);
    }
}
