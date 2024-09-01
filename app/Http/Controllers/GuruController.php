<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;

class GuruController extends Controller
{

    public function index()
    {
        return view('guru.index');
    }

    /**
     * Fetch data for DataTables via AJAX.
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = Guru::with(['user', 'kelas'])->select('guru.*');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('name', function($row) {
                    return $row->user->name;
                })
                ->addColumn('email', function($row) {
                    return $row->user->email;
                })
                ->addColumn('kelas', function($row) {
                    return $row->kelas->name; // Assuming 'name' is a field in Kelas
                })
                ->addColumn('actions', function($row) {
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="edit btn btn-primary btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="delete btn btn-danger btn-sm" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }


    /**
     * Store a newly created Guru in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'nik' => 'required|unique:guru,nik',
                'user_id' => 'required|exists:users,id',
                'kelas_id' => 'required|exists:kelas,id',
                // Add other validation rules as needed
            ]);

            // Create the Guru record
            Guru::create([
                'nik' => $request->nik,
                'user_id' => $request->user_id,
                'kelas_id' => $request->kelas_id,
                // Add other fields as needed
            ]);

            // Redirect back to the create page with a success message
            return redirect()->route('guru.index')->with('success', 'Guru created successfully.');
        } catch (\Exception $e) {
            // Catch any exception and return an error response
            return redirect()->route('guru.index')->with('error', 'Failed to create Guru: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified Guru.
     */
    public function edit(Guru $guru)
    {
        $guru->load(['user', 'kelas']);
        return response()->json($guru);
    }

    /**
     * Update the specified Guru in storage.
     */
    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nik' => 'required|unique:guru,nik,' . $guru->id,
            'user_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            // Add other validation rules as needed
        ]);

        $guru->update([
            'nik' => $request->nik,
            'user_id' => $request->user_id,
            'kelas_id' => $request->kelas_id,
            // Update other fields as needed
        ]);

        return response()->json(['success' => 'Guru updated successfully.']);
    }

    /**
     * Remove the specified Guru from storage.
     */
    public function destroy(Guru $guru)
    {
        $guru->delete();
        return response()->json(['success' => 'Guru deleted successfully.']);
    }
}
