<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Hash;

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
                ->addColumn('name', function ($row) {
                    return $row->user->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->user->email;
                })
                ->addColumn('kelas', function ($row) {
                    return $row->kelas->name;
                })
                ->addColumn('gender', function ($row) {
                    return ucfirst($row->gender); // Display gender
                })
                ->addColumn('actions', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary btn-sm" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="delete btn btn-danger btn-sm" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>';
                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function countData()
    {
        $totalGuru = Guru::count();
        $maleGuru = Guru::where('gender', 'laki')->count();
        $femaleGuru = Guru::where('gender', 'perempuan')->count();

        return response()->json([
            'totalGuru' => $totalGuru,
            'maleGuru' => $maleGuru,
            'femaleGuru' => $femaleGuru,
        ]);
    }


    /**
     * Store a newly created Guru in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->user_type === 'new') {
                // Validate new user data
                $request->validate([
                    'username' => 'required|string|max:255|unique:users,username',
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|min:8',
                    'kelas_id' => 'required|exists:kelas,id',
                    'gender' => 'required|in:laki,perempuan',
                ]);

                // Create the new user
                $user = User::create([
                    'username' => $request->username,
                    'name' => $request->name,
                    'email' => $request->email,
                    'nik' => $request->nik,
                    'password' => Hash::make($request->password),
                    'status'=>'y',
                ]);

                // Assign 'guru' role to the new user
                $user->syncRoles(['guru']);

                // Use the new user's ID for the guru record
                $userId = $user->id;
                Guru::create([
                    'nik' => $request->nik,
                    'user_id' => $userId,
                    'kelas_id' => $request->kelas_id,
                    'gender' => $request->gender,
                ]);

            } else {
                // Validate existing user data
                $request->validate([
                    'nik' => 'required|unique:guru,nik,' . $request->id,
                    'user_id' => 'required|exists:users,id',
                    'kelas_id' => 'required|exists:kelas,id',
                    'gender' => 'required|in:laki,perempuan',
                ]);

                // Use the selected existing user ID for the guru record
                $userId = $request->user_id;
            }

            // Create or update Guru record
            if ($request->has('guru_id')) {
                $guru = Guru::find($request->guru_id);

                if ($guru) {
                    $guru->update([
                        'nik' => $request->nik,
                        'user_id' => $userId,
                        'kelas_id' => $request->kelas_id,
                        'gender' => $request->gender,
                    ]);

                    return redirect()->route('guru.index')->with('success', 'Guru updated successfully.');
                } else {
                    return redirect()->route('guru.index')->with('error', 'Guru not found.');
                }
            } else {
                Guru::create([
                    'nik' => $request->nik,
                    'user_id' => $userId,
                    'kelas_id' => $request->kelas_id,
                    'gender' => $request->gender,
                ]);

                return redirect()->route('guru.index')->with('success', 'Guru created successfully.');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('guru.index')->with('error', 'Failed to process request: ' . $e->getMessage());
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
