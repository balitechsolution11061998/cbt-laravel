<?php

namespace App\Http\Controllers\Auth;

use App\Events\GagalLoginEvent;
use App\Events\LoginEvent;
use App\Http\Controllers\Controller;
use App\Models\LogActivities;
use App\Models\User;
use App\Models\VerificationCode;
use App\Providers\RouteServiceProvider;
use App\Rules\MatchOldPassword;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $login_attempts;
    private $timestamps;


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
	{
		return 'username';
	}

    public function incrementLoginAttempts($user)
    {
        // dd(Auth::user());
        // $user = new User;
        // $user->timestamps = false;
        // $user->login_attempts = ($this->login_attempts + 1);
        // $user->save();

        $user = new User();
        $user->incrementLoginAttempts();

    }

    public function getLoginAttempts()
    {
        return $this->login_attempts;
    }

    public function authenticate(Request $request)
    {
        try {
            //code...
            $credentials = $request->only($this->username(), 'password');
            $credentials['status'] = 'y';

            // Mengecek apakah pengguna dengan kredensial yang diberikan ada
            $user = Auth::getProvider()->retrieveByCredentials($credentials);
            if ($user) {
                // Mengecek apakah password sesuai
                if (Hash::check($credentials['password'], $user->getAuthPassword())) {
                    $startTime = microtime(true);
                    // Autentikasi berhasil
                    Auth::login($user);
                    $request->session()->regenerate();
                    $user->login_attempts = 0;
                    $user->save();
                    event(new LoginEvent($user));
                    $endTime = microtime(true);
                    $executionTime = $endTime - $startTime;


                    return redirect()->route('home');
                }else if ($user->login_attempts >= 3) {
                    Auth::logout();
                    if($user->channel_id == null){
                        return response()->json([
                            'success' => false,
                            'message' => 'Silahkan menghubungi administrator untuk menginputkan channel id atau whatshapp nomor',
                        ],401);

                    }else{
                        $otpData = $this->generateOtp($user->id);
                        event(new GagalLoginEvent($user,$otpData));




                        return redirect()->route('login')
                        ->with('toast', [
                            'title' => 'Login Gagal!',
                            'message' => 'Silahkan cek email untuk perubahan password.',
                            'type' => 'error' // You can use 'success', 'info', 'warning', 'error' based on your toast library
                        ]);
                    }

                }

            }



            // Jika percobaan login gagal, maka tambahkan 1 ke login_attempts
            if ($user) {
                $user->incrementLoginAttempts($user);
            }

            // Autentikasi gagal
            return redirect()->route('login')
            ->with('toast', [
                'title' => 'Login Gagal!',
                'message' => 'Password atau username tidak terdapat di sistem atau status tidak aktif.',
                'type' => 'error' // You can use 'success', 'info', 'warning', 'error' based on your toast library
            ]);
        } catch (Exception $th) {
            //throw $th;
            return redirect()->route('login')
            ->with('toast', [
                'title' => 'Login Gagal!',
                'message' => 'Password atau username tidak terdapat di sistem atau status tidak aktif.',
                'type' => 'error' // You can use 'success', 'info', 'warning', 'error' based on your toast library
            ]);
        }

    }

    public function generateOtp($userData)
    {

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('user_id', $userData)->latest()->first();

        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }else{
            // Create a New OTP
            return VerificationCode::create([
                'user_id' => $userData,
                'otp' => rand(123456, 999999),
                'expire_at' => Carbon::now()->addMinutes(10)
            ]);

        }


    }

    public function verification($user_id)
    {
        return view('auth.otpverification')->with([
            'user_id' => $user_id
        ]);
    }

    public function verificationOtp(Request $request)
    {
        try{
                                    #Validation
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'otp1' => 'required',
            'otp2' => 'required',
            'otp3' => 'required',
            'otp4' => 'required',
            'otp5' => 'required',
            'otp6' => 'required',
        ]);

        $dataOtp = $request->otp1.$request->otp2.$request->otp3.$request->otp4.$request->otp5.$request->otp6;
        #Validation Logic
        $verificationCode = VerificationCode::where('user_id', $request->user_id)->where('otp', $dataOtp)->first();
        $now = Carbon::now();
        if ($verificationCode == null) {
            return redirect()->back()->with('error', 'Your OTP is not correct');
        }elseif($verificationCode && $now->isAfter($verificationCode->expire_at)){
            return redirect()->route('otp.verification', ['user_id' => $request->user_id])->with('error', 'Your OTP has been expired');
        }

        $user = User::whereId($request->user_id)->first();

        if($user){
            // Expire The OTP
            $verificationCode->update([
                'expire_at' => Carbon::now()
            ]);
            return redirect()->route('resetPassword', ['user_id' => $request->user_id]);

        }
            return redirect()->route('otp.verification', ['user_id' => $request->user_id])->with('error', 'Your Otp is not correct');
        }catch(Exception $e){
            return redirect()->route('otp.verification', ['user_id' => $request->user_id])->with('error', 'Your Otp is not correct');


        }

    }

    public function resetPassword($user_id){
        return view('auth.passwords.reset')->with([
            'user_id' => $user_id
        ]);
    }

    public function updatePassword(Request $request){
        try {
            //code...
            $validationRules = [
                'password' => 'required',
                'new_password' => 'required',
                'new_password_confirmation' => 'same:new_password',
            ];

            $customMessages = [
                'new_password_confirmation.same' => 'The confirmation password must match the new password.',
            ];

            $request->validate($validationRules, $customMessages);

            $data = User::find($request->user_id)->update(['password'=> Hash::make($request->new_password)]);

            return response()->json([
                'success' => true,
                'message' => 'Password sukses diganti silahkan login kembali menggunakan password yang telah diganti',
            ], 200);
        } catch (\Exception $th) {
            dd($th->getMessage());
            return redirect()->route('resetPassword', ['user_id' => $request->user_id])->with('error', 'Reset password failed');

        }

    }

    // protected function credentials(Request $request)
	// {
    //     $credentials = $request->only($this->username(), 'password');
    //     $credentials['status'] = 'y';

    //     return $credentials;


	// 	// return $request->only($this->username(), 'password');
	// }
}
