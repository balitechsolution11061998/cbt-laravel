<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $token = $request->query('token');

        $qrCodeData = [
            'token' => $token,
        ];

        // Konversi array ke format JSON
        $qrCodeDataJson = json_encode($qrCodeData);

        // Kirim data QR code ke view
        return view('login.siswa', ['qrCodeData' => $qrCodeDataJson]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_login(Request $request)
    {
        $email      = $request->input('email');
        $password   = $request->input('password');

        if(Auth::guard('web')->attempt(['email' => $email, 'password' => $password])) {
            return response()->json([
                'success' => true
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login Gagal!'
            ], 401);
        }

    }
}
