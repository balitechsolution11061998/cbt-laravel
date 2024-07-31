<?php

namespace App\Repositories\Auth;

use LaravelEasyRepository\Repository;

interface AuthRepository extends Repository{
    public function findActiveUserByUsername($filterDate, $filterSupplier,$remember_me);
}
