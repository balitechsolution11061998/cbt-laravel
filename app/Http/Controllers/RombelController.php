<?php

namespace App\Http\Controllers;

use App\Models\Rombel;
use App\Models\Kelas;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    public function index()
    {
        return view('rombels.index');
    }

    public function getRombelOptions()
    {
        // Fetch all classes
        $rombel = Rombel::select('id', 'nama_rombel')->get();

        // Return the classes as JSON
        return response()->json($rombel);
    }

    public function getRombelData()
{
    $rombels = Rombel::count();

    return response()->json([
        'rombelCounts' => $rombels,
    ]);
}

    public function data()
    {
        return datatables()->of(Rombel::with('kelas')->select('rombels.*'))
            ->addIndexColumn()
            ->addColumn('action', function($row){
                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm editRombel" data-id="'.$row->id.'">Edit</a>';
                $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm deleteRombel" data-id="'.$row->id.'">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required',
            'nama_rombel' => 'required|max:255',
        ]);

        Rombel::updateOrCreate(['id' => $request->id], [
            'kelas_id' => $request->kelas_id,
            'nama_rombel' => $request->nama_rombel,
        ]);

        return response()->json(['success' => 'Rombel saved successfully.']);
    }

    public function edit($id)
    {
        $rombel = Rombel::find($id);
        return response()->json($rombel);
    }

    public function destroy($id)
    {
        Rombel::find($id)->delete();
        return response()->json(['success' => 'Rombel deleted successfully.']);
    }
}
