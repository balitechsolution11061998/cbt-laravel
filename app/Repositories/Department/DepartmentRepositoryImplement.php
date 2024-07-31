<?php

namespace App\Repositories\Department;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Department;

class DepartmentRepositoryImplement extends Eloquent implements DepartmentRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Department $model)
    {
        $this->model = $model;
    }

    public function getAllDepartments()
    {
        return Department::all();
    }

}
