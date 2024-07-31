<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuti; // Adjust according to your model namespace
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CutiController extends Controller
{
    //
    public function index()
    {
        return view('cuti.index');
    }

    public function store(Request $request)
    {
        $rules = [
            'kodeCuti' => 'required|string|max:255',
            'namaCuti' => 'required|string|max:255',
            'jumlahHari' => 'required|integer|min:1',
        ];

        // Custom rule to ensure kodeCuti is unique
        if ($request->id != null) {
            $rules['kodeCuti'] .= '|unique:cuti,kode_cuti,' . $request->input('id');
        } else {
            $rules['kodeCuti'] .= '|unique:cuti,kode_cuti';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        try {
            DB::beginTransaction();

            if ($request->id != null) {
                // Update existing cuti
                $cuti = Cuti::findOrFail($request->input('id'));
                $cuti->kode_cuti = $request->input('kodeCuti');
                $cuti->nama_cuti = $request->input('namaCuti');
                $cuti->jumlah_hari = $request->input('jumlahHari');
                $cuti->save();

                $message = 'Data cuti berhasil diperbarui';
            } else {
                // Create new cuti
                $cuti = new Cuti();
                $cuti->kode_cuti = $request->input('kodeCuti');
                $cuti->nama_cuti = $request->input('namaCuti');
                $cuti->jumlah_hari = $request->input('jumlahHari');
                $cuti->save();

                $message = 'Data cuti berhasil ditambahkan';
            }

            DB::commit();

            return response()->json(['success' => $message], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function data()
    {
        try {
            $cuti = Cuti::select(['id', 'kode_cuti', 'nama_cuti', 'jumlah_hari']);

            return DataTables::of($cuti)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editButton = '<a href="javascript:void(0)" class="edit btn btn-success btn-sm" onclick="editCuti(' . $row->id . ')">
                                    <i class="fas fa-edit"></i>
                                  </a>';
                    $deleteButton = '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm" onclick="deleteCuti(' . $row->id . ')">
                                    <i class="fas fa-trash"></i>
                                  </a>';
                    return $editButton . ' ' . $deleteButton;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $cuti = Cuti::findOrFail($id);
            return response()->json(['cuti' => $cuti], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage()], 500);
        }
    }

    public function count()
    {
        // Count the total number of leave records
        $cutiCount = Cuti::count(); // Replace Cuti with your actual model

        // Return the count as JSON
        return response()->json(['count' => $cutiCount]);
    }

    public function delete($id)
    {
        try {
            $cuti = Cuti::findOrFail($id);
            $cuti->delete();

            return response()->json(['success' => 'Data cuti berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()], 500);
        }
    }
}
