<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KantorCabangController extends Controller
{
    //
    public function index(){
        return view('kantor_cabang.index');
    }

    public function data(Request $request)
    {
        if ($request->ajax()) {
            $data = Cabang::with('provinsi', 'kabupaten','kecamatan','kelurahan')
                        ->select(['id', 'kode_cabang', 'name', 'provinsi_id','kabupaten_id','kecamatan_id','kelurahan_id','radius'])
                        ->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function($row){
                    $btn = '<a href="#" class="btn-edit"><i class="fas fa-edit"></i></a>';
                    $btn .= ' <a href="#" class="btn-delete"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }


        return view('kantor_cabang.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_cabang' => 'required|unique:cabang,kode_cabang,' . $request->id,
            'name' => 'required',
            'provinsi_id' => 'required',
            'kabupaten_id' => 'required',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'radius' => 'required',
        ]);

        try {
            $cabang = $request->id ? Cabang::find($request->id) : new Cabang();
            $cabang->kode_cabang = $request->kode_cabang;
            $cabang->name = $request->name;
            $cabang->provinsi_id = $request->provinsi_id;
            $cabang->kabupaten_id = $request->kabupaten_id;
            $cabang->kecamatan_id = $request->kecamatan_id;
            $cabang->kelurahan_id = $request->kelurahan_id;  // Corrected this line
            $cabang->radius = $request->radius;
            $cabang->save();

            $message = $request->id ? 'Cabang updated successfully.' : 'Cabang added successfully.';

            return response()->json(['success' => $message]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving the cabang: ' . $e->getMessage()], 500);
        }
    }
    public function edit($id)
    {
        try {
            $cabang = Cabang::findOrFail($id);
            return response()->json(['data' => $cabang]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cabang not found: ' . $e->getMessage()], 404);
        }
    }
    // KantorCabangController.php

public function delete($id)
{
    try {
        $cabang = Cabang::findOrFail($id);
        $cabang->delete();

        return response()->json(['success' => 'Cabang deleted successfully.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error deleting cabang: ' . $e->getMessage()], 500);
    }
}


}
