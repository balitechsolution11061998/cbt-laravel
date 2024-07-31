<?php

namespace App\Services\Auth;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthServiceImplement extends ServiceApi implements AuthService
{

    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected $title = "";
    /**
     * uncomment this to override the default message
     * protected $create_message = "";
     * protected $update_message = "";
     * protected $delete_message = "";
     */

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(AuthRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function checkLogin($username, $password, $remember_me)
    {
        $user = $this->mainRepository->findActiveUserByUsername($username, $password, $remember_me);
        // If user is found and the password matches
        if ($user && Hash::check($password, $user->password)) {
            // Log in the user with the "remember me" option
            Auth::login($user, $remember_me);

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Invalid credentials.'];
        }
    }
}
