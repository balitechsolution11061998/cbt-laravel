<?php

namespace App\Services\Department;

use LaravelEasyRepository\BaseService;

interface DepartmentService extends BaseService{
    public function getDepartments();
}
