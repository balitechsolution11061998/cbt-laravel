<?php

namespace App\Repositories\Department;

use LaravelEasyRepository\Repository;
use Illuminate\Support\Collection;

interface DepartmentRepository extends Repository{

    // Write something awesome :)
    public function getAllDepartments();
}
