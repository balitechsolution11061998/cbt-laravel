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
            'id' => 'nullable|exists:siswas,id', // Validate ID for update, if provided
            'kelas_id' => 'required|exists:kelas,id',
            'nama' => 'required|string|max:255',
            'nis' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',

            // Validation rules for User
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8', // Password can be updated, should be hashed before saving
        ]);

        \DB::transaction(function () use ($request, $data) {
            // Create or update the Siswa record
            $siswa = Siswa::updateOrCreate(
                ['id' => $request->id],
                [
                    'kelas_id' => $data['kelas_id'],
                    'nama' => $data['nama'],
                    'nis' => $data['nis'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                ]
            );

            // Prepare User data
            $userData = [
                'username' => $data['nis'],
                'email' => $data['email'], // Use the provided email
                'name' => $data['nama'],
                'nik' => $data['nis'],
            ];

            // Only hash the password if it's provided
            if ($data['password']) {
                $userData['password'] = bcrypt($data['password']);
            }

            // Create or update the User record based on `username` (nis)
            $user = User::updateOrCreate(
                ['username' => $data['nis']],
                $userData
            );

            // Optional: Associate User with Siswa if necessary
            // $siswa->user()->associate($user)->save(); // Example if you have such a relationship
        });

        return response()->json(['success' => 'Siswa and User saved successfully.']);
    }

    public function edit($id)
    {
        $siswa = Siswa::with('users')->where('id',$id)->first();
        return response()->json($siswa);
    }

    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();
        return response()->json(['success' => 'Siswa deleted successfully.']);
    }

    public function getStudentData()
    {
        $user = auth()->user();

        // Query for counting total students
        $totalQuery = Siswa::query();

        // Query for counting male students
        $maleQuery = Siswa::where('jenis_kelamin', 'L');

        // Query for counting female students
        $femaleQuery = Siswa::where('jenis_kelamin', 'P');

        // Check if the authenticated user has the 'guru' role
        if ($user->hasRole('guru')) {
            // Assuming 'guru' has access to 'kelas_id'
            $totalQuery->where('kelas_id', $user->guru->kelas_id);
            $maleQuery->where('kelas_id', $user->guru->kelas_id);
            $femaleQuery->where('kelas_id', $user->guru->kelas_id);
        }

        // Get the counts
        $total = $totalQuery->count();
        $male = $maleQuery->count();
        $female = $femaleQuery->count();

        // Get the students with their related class
        $studentsQuery = Siswa::with('kelas');

        if ($user->hasRole('guru')) {
            $studentsQuery->where('kelas_id', $user->guru->kelas_id);
        }

        $students = $studentsQuery->get();

        return response()->json([
            'total' => $total,
            'male' => $male,
            'female' => $female,
            'students' => $students,
        ]);
    }


    public function data()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Query the Siswa data with related kelas and users
        $query = Siswa::with('kelas', 'users');

        // Check if the authenticated user has the 'guru' role
        if ($user->hasRole('guru')) {
            // Assuming 'guru' has access to 'kelas_id'
            $query->where('kelas_id', $user->guru->kelas_id);
        }

        return datatables()->of($query->get())
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                // Check if the users relationship exists
                $userId = $row->users->id ?? null;
                $studentName = addslashes($row->users->name ?? '');
                $studentClass = addslashes($row->kelas->name ?? '');
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
