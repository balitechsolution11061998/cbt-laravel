<?php

namespace App\Http\Controllers;

use App\Mail\AccountDetailsMail;
use App\Mail\QRCodeEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use QrCode;
use Illuminate\Support\Facades\Storage;
use App\Helpers\HashHelper;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    //
    use LogsActivity;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return view('users.index');
    }

    public function data(Request $request)
    {
        try {
            $this->logActivity('Accessed create page', 'User accessed the create user page');

            if ($request->ajax()) {
                $data = $this->userService->getData($request);

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                        $btn .= '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            $this->logError('An error occurred while accessing the create page', $e);

            return $e->getMessage();
        }
    }


    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        // Generate a new password (optional)
        $newPassword = Str::random(8); // Example: Generate a random 8-character password
        $user->password_show = $newPassword;

        // Update user's password
        $user->password = bcrypt($newPassword);
        $user->save();

        // Send email with password reset information
        Mail::to($user->email)->send(new ResetPasswordEmail($user, $newPassword));

        return redirect()->back()->with('success', 'Password reset email sent successfully.');
    }

    public function edit($id)
    {
        return view('users.create');
    }

    public function dataEdit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function create()
    {
        try {
            // Log the activity of accessing the create page using the trait
            $this->logActivity('Accessed create page', 'User accessed the create user page');

            // Return the view for creating a user
            return view('users.create');
        } catch (\Exception $e) {
            // Log the error
            $this->logError('An error occurred while accessing the create page', $e);

            return $e->getMessage();
        }
    }


    public function store(Request $request)
    {

        // Determine if this is a create or update operation
        $isUpdate = $request->has('id');
        $user = $isUpdate ? User::findOrFail($request->input('id')) : new User();
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:10',
                $isUpdate ? Rule::unique('users')->ignore($user->id) : 'unique:users',
            ],
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                $isUpdate ? Rule::unique('users')->ignore($user->id) : 'unique:users',
            ],
            'password' => $isUpdate ? 'sometimes|string|min:8' : 'required|string|min:8',
            'departments' => 'required|integer',
            'jabatan' => 'required|string|max:255',
            'cabang' => 'required|string|max:255',
            'no_handphone' => 'required|string|max:15',
            'nik' => 'required|string|max:16',
            'join_date' => 'required|date',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'nullable|string',
            'about_us' => 'nullable|string',
            'status' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle the photo upload
        if ($request->hasFile('photo')) {
            $imageName = time() . '.' . $request->photo->extension();
            $uploadedImage = $request->photo->storeAs('images', $imageName, 'public');
            $photoPath = 'storage/images/' . $imageName; // Store the path in the database
            $user->photo = $photoPath;
        }

        try {
            // Set user attributes
            $user->username = $request->input('username');
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if ($request->filled('password')) {
                $user->password_show = $request->input('password');
                $user->password = bcrypt($request->input('password'));
            }
            $user->kode_dept = $request->input('departments');
            $user->kode_jabatan = $request->input('jabatan');
            $user->kode_cabang = $request->input('cabang');
            $user->phone_number = $request->input('no_handphone');
            $user->nik = $request->input('nik');
            $user->alamat = $request->input('address');
            $user->about_us = $request->input('about_us');
            $user->join_date = $request->input('join_date');
            $user->status = $request->input('status') === 'on' ? 'y' : 'n';

            $user->save();

            $message = $isUpdate ? 'User updated successfully' : 'User created successfully';
            return response()->json(['message' => $message], $isUpdate ? 200 : 201);
        } catch (\Exception $e) {
            // Log the error using the LogsErrors trait
            $this->logError('An error occurred while accessing the save page', $e);

            // Return a JSON response with the error message
            return response()->json(['error' => 'Failed to save user', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
            $user->delete();

            DB::commit();

            return Response::json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());

            return Response::json(['error' => 'Failed to delete user', 'message' => $e->getMessage()], 500);
        }
    }

    public function sendAccountDetails(Request $request)
    {
        $request->validate([
            'receiver' => 'required|email',
            'contact_method' => 'required|string',
        ]);

        // Fetch user details from the database
        $user = User::where('email', $request->receiver)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $details = [
            'name' => $user->name,
            'username' => $user->username,
            'password' => $user->password_show,
            'login_url' => env('APP_URL'),
        ];

        // Send email with account details
        Mail::to($request->receiver)->send(new AccountDetailsMail($details));

        return response()->json(['message' => 'Account details have been sent successfully.'], 200);
    }






    public function downloadQRCodePDF($userId)
    {
        // Fetch user and related student data
        $user = User::with('siswa')->find($userId);

        if (!$user) {
            return abort(404, 'User not found');
        }

        $student = $user->siswa;
        // Generate QR code as a base64 image
        $qrCode = \QrCode::format('png')->size(200)->generate($user->qr_code);

        // Pass data to the PDF view
        $pdf = Pdf::loadView('pdf.qr_code_with_data', [
            'user' => $user,
            'student' => $student,
            'qrCode' => $qrCode,
        ]);

        // Return the generated PDF for download
        return $pdf->download("qr-code-{$userId}.pdf");
    }



    public function generateQRCode($userId)
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                return response()->json(['error' => 'User not found.'], 404);
            }

            // Generate a unique token for login
            $token = Str::random(60);
            $baseUrl = config('app.url');
            $loginUrl = $token;
            $encodedUrl = mb_convert_encoding($loginUrl, 'UTF-8');

            // Generate the QR code
            $qrCode = QrCode::format('png')->size(300)->generate($encodedUrl);

            // Save QR code and token to the database
            $user->qr_code = base64_encode($qrCode);
            $user->qr_code_token = $token; // Assuming you have a column for storing the token
            $user->save();

            $qrCodeUrl = 'data:image/png;base64,' . base64_encode($qrCode);

            \Mail::to($user->email)->send(new \App\Mail\QRCodeEmail($qrCodeUrl, $loginUrl));

            return response()->json(['qr_code_url' => $qrCodeUrl, 'login_url' => $loginUrl]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json(['error' => 'Failed to generate QR code.'], 500);
        }
    }
    public function profile(Request $request)
    {
        $hashedId = $request->query('id');
        $user = User::with(['siswa.kelas', 'siswa.kelas.ujian.paketSoal'])->get()->first(function($user) use ($hashedId) {
            return Hash::check($user->id, $hashedId);
        });

        $currentUjian = null;
        $now = now(); // Get current datetime

        if ($user && $user->siswa && $user->siswa->kelas) {
            $currentUjian = $user->siswa->kelas->ujian->filter(function($ujian) use ($now) {
                $ujianStartTime = \Carbon\Carbon::parse($ujian->waktu_mulai);
                $ujianEndTime = $ujianStartTime->copy()->addMinutes($ujian->durasi); // Assuming 'durasi' is the exam duration in minutes

                return $now->between($ujianStartTime, $ujianEndTime);
            });

            if ($currentUjian && $currentUjian->isNotEmpty()) {
                $ujianIds = $currentUjian->pluck('id');
                $hasUjianHistory = \App\Models\UjianHistory::whereIn('ujian_id', $ujianIds)
                    ->where('siswa_id', $user->siswa->id)
                    ->exists();

                if ($hasUjianHistory) {
                    $currentUjian = null; // Set currentUjian to null if there's a history
                }
            }
        }

        if ($user) {
            return view('users.profile', [
                'user' => $user,
                'currentUjian' => $currentUjian,
            ])->with('error', $currentUjian ? null : 'Tidak ada ujian saat ini.');
        } else {
            return redirect()->route('login')->with('error', 'Invalid user.');
        }
    }














    public function setjamkerja($nik)
    {
        try {
            $karyawan = DB::table('users')->where('nik', $nik)->first();
            $jamkerja = DB::table('jam_kerja')->orderBy('nama_jk')->get();
            $cekjamkerja = DB::table('konfigurasi_jam_kerja')->where('nik', $nik)->count();
            $cekjamkerjaByDate = DB::table('konfigurasi_jam_kerjaByDate')->where('nik', $nik)->count();
            $jamKerjaUserDay = DB::table('konfigurasi_jam_kerja')->where('nik', $nik)->get();
            $bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            if ($cekjamkerja > 0) {
                $setjamkerja = DB::table('konfigurasi_jam_kerja')->where('nik', $nik)->get();
                $setJamkerjaByDate = DB::table('konfigurasi_jam_kerjaByDate')->where('nik', $nik)->get();
                return response()->json([
                    'status' => 'success',
                    'karyawan' => $karyawan,
                    'jamkerja' => $jamkerja,
                    'cekjamkerja' => $cekjamkerja,
                    'setjamkerja' => $setjamkerja,
                    'setJamkerjaByDate' => $setJamkerjaByDate,
                    'bulan' => $bulan
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'karyawan' => $karyawan,
                    'jamkerja' => $jamkerja,
                    'bulan' => $bulan
                ]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Log the exception message
            // Return a JSON response with the error message
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengatur jam kerja. Silakan coba lagi.'
            ], 500);
        }
    }
}
