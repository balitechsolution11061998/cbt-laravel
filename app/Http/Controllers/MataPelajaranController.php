<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MataPelajaranController extends Controller
{

    public function index(){
        return view('mata-pelajaran.index');
    }
    public function data(Request $request)
    {
        $data = MataPelajaran::latest()->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm editMataPelajaran" title="Edit"><i class="fas fa-edit"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm deleteMataPelajaran" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function getMataPelajaranData()
    {
        // Fetch total Mata Pelajaran count
        $totalMataPelajaran = MataPelajaran::count();

        // Fetch detailed breakdown


        return response()->json([
            'total_mata_pelajaran' => $totalMataPelajaran,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:mata_pelajaran',
            'nama' => 'required',
        ]);

        MataPelajaran::create($request->all());

        return response()->json(['success'=>'Mata Pelajaran created successfully.']);
    }

    public function edit($id)
    {
        $mataPelajaran = MataPelajaran::find($id);
        return response()->json($mataPelajaran);
    }

    public function dataoptions()
    {
        // Fetch all classes
        $kelas = MataPelajaran::select('id', 'nama')->get();

        // Return the classes as JSON
        return response()->json($kelas);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:mata_pelajaran,kode,' . $id,
            'nama' => 'required',
        ]);

        $mataPelajaran = MataPelajaran::find($id);
        $mataPelajaran->update($request->all());

        return response()->json(['success'=>'Mata Pelajaran updated successfully.']);
    }

    public function destroy($id)
    {
        MataPelajaran::find($id)->delete();
        return response()->json(['success'=>'Mata Pelajaran deleted successfully.']);
    }
}
