<?php

// app/Http/Controllers/SiswaController.php
namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Rombel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index()
    {
        return view('siswa.index');
    }

    public function store(Request $request)
    {
        // Validate the request data for Siswa
        $data = $request->validate([
            'siswa_id' => 'nullable|exists:siswa,id', // Validate ID for update, if provided
            'rombel_id' => 'required|exists:rombels,id',
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',

            // Validation rules for User
            'user_id' => 'nullable|exists:users,id', // Validate ID for update, if provided
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8', // Password can be updated, should be hashed before saving
        ]);

        DB::transaction(function () use ($request, $data) {
            // Create or update the Siswa record
            $siswa = Siswa::updateOrCreate(
                ['id' => $request->siswa_id],
                [
                    'rombel_id' => $data['rombel_id'],
                    'nama' => $data['nama'],
                    'nis' => $data['nis'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                ]
            );

            // Create or update the User record
            $userData = [
                'username' => $data['nis'],
                'email' => $data['nama']."@gmail.com",
                'name' => $data['nama'],
                'nik' => $data['nis'],
                'password' => $data['password'] ? bcrypt($data['password']) : null,
            ];

            $user = User::updateOrCreate(
                ['id' => $request->user_id],
                $userData
            );

            // Optional: Associate User with Siswa if necessary
            // $siswa->user()->associate($user)->save(); // Example if you have such a relationship
        });

        return response()->json(['success' => 'Siswa and User saved successfully.']);
    }


    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return response()->json($siswa);
    }

    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();
        return response()->json(['success' => 'Siswa deleted successfully.']);
    }

    public function getStudentData()
    {
        $total = Siswa::count();
        $male = Siswa::where('jenis_kelamin', 'L')->count();
        $female = Siswa::where('jenis_kelamin', 'P')->count();

        $students = Siswa::with('rombel.kelas')->get();

        // Group students by rombel and kelas and count the number of students in each group
        $rombelKelasCounts = $students->groupBy(function($student) {
            return $student->rombel->nama_rombel . ' - ' . $student->rombel->kelas->name;
        })->map(function ($group) {
            return $group->count();
        });

        return response()->json([
            'total' => $total,
            'male' => $male,
            'female' => $female,
            'rombelKelasCounts' => $rombelKelasCounts,
        ]);
    }


    public function data()
    {
        return datatables()->of(Siswa::with('rombel', 'users')->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                // Check if the users relationship exists
                $userId = $row->users->id ?? null;
                $studentName = addslashes($row->users->name ?? '');
                $studentClass = addslashes($row->rombel->nama_rombel ?? '');
                $nis = $row->nis; // Assuming 'nis' is a column in the 'Siswa' model
                $gender = addslashes($row->jenis_kelamin ?? ''); // Assuming 'jenis_kelamin' is a column in the 'Siswa' model

                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm editSiswa" data-id="'.$row->id.'"><i class="fas fa-edit"></i></a>';
                $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm deleteSiswa" data-id="'.$row->id.'"><i class="fas fa-trash-alt"></i></a>';

                // Only create the QR code button if $userId is not null
                if ($userId) {
                    $btn .= ' <a href="javascript:void(0)" class="qr-code btn btn-success btn-sm" onclick="createQRCode('.$userId.', \''.$studentName.'\', \''.$studentClass.'\', \''.$nis.'\', \''.$gender.'\')"><i class="fas fa-qrcode"></i></a>';
                }

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }






    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return response()->json(['success' => 'Siswa imported successfully.']);
    }
}
