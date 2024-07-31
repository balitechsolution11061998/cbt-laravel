<?php

namespace App\Http\Controllers;

use App\Models\JamKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class JamKerjaController extends Controller
{
    //
    public function index(){
        return view('jam_kerja.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = JamKerja::select(['id', 'kode_jk', 'nama_jk', 'awal_jam_masuk', 'jam_masuk', 'akhir_jam_masuk', 'jam_pulang', 'lintas_hari']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $editBtn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="editJamKerja('.$row->id.')"><i class="fas fa-edit"></i></a>';
                    $deleteBtn = ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" onclick="deleteJamKerja('.$row->id.')"><i class="fas fa-trash-alt"></i></a>';
                    return $editBtn . $deleteBtn;
                })
                ->editColumn('lintas_hari', function($row) {
                    return $row->lintas_hari == 1 ? 'Yes' : 'No';
                })
                ->rawColumns(['action'])
                ->make(true);
        }


    }

    public function store(Request $request)
    {
        try {
            $data = [
                'kode_jk' => $request->kodeJamKerja,
                'nama_jk' => $request->namaJamKerja,
                'awal_jam_masuk' => $request->awalJamMasuk,
                'jam_masuk' => $request->jamMasuk,
                'akhir_jam_masuk' => $request->akhirJamMasuk,
                'jam_pulang' => $request->jamPulang,
                'lintas_hari' => $request->lintasHari,
            ];

            if ($request->id) {
                // Update
                $jamKerja = JamKerja::findOrFail($request->id);
                $jamKerja->fill($data);
                $jamKerja->save();

                return response()->json([
                    'success' => 'Jam Kerja successfully updated!',
                    'data' => $jamKerja
                ]);
            } else {
                // Create
                $jamKerja = JamKerja::create($data);

                return response()->json([
                    'success' => 'Jam Kerja successfully created!',
                    'data' => $jamKerja
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save Jam Kerja: ' . $e->getMessage()
            ], 500);
        }
    }
    public function edit($id)
    {
        try {
            // Assuming JamKerja is your model
            $jamKerja = JamKerja::findOrFail($id);

            // Return the data in JSON format (or you can render a view if needed)
            return response()->json($jamKerja);

        } catch (\Exception $e) {
            // Handle the exception
            return response()->json([
                'error' => 'An error occurred while fetching the record.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $jamKerja = JamKerja::findOrFail($id);
            $jamKerja->delete();

            return response()->json([
                'success' => 'Jam Kerja successfully deleted!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete Jam Kerja: ' . $e->getMessage()
            ], 500);
        }
    }

}
