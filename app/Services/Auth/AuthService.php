<?php

namespace App\Services\Auth;

use LaravelEasyRepository\BaseService;

interface AuthService extends BaseService{
    public function checkLogin($username, $password,$remember_me);
}
