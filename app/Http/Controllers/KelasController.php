<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    //
    public function index(){
        return view('kelas.index');
    }

    public function data()
    {
        return datatables()->of(Kelas::query())
            ->addIndexColumn() // This will add DT_RowIndex
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm editKelas" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="fas fa-edit"></i></a>';
                $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm deleteKelas" data-id="'.$row->id.'" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getKelasData()
{
    $total_kelas = Kelas::count();

    return response()->json([
        'total_kelas' => $total_kelas,
    ]);
}

    public function dataoptions()
    {
        // Fetch all classes
        $kelas = Kelas::select('id', 'name')->get();

        // Return the classes as JSON
        return response()->json($kelas);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'nullable|integer',
            'name' => 'required|max:255',
            'description' => 'required|max:500',
        ]);

        $kelas = Kelas::updateOrCreate(
            ['id' => $request->id],
            ['name' => $request->name, 'description' => $request->description]
        );

        return response()->json(['success' => 'Kelas saved successfully.']);
    }

    public function edit($id)
    {
        $kelas = Kelas::find($id);
        return response()->json($kelas);
    }

    public function destroy($id)
    {
        Kelas::find($id)->delete();
        return response()->json(['success' => 'Kelas deleted successfully.']);
    }
}
