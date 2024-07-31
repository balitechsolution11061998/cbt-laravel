<?php

namespace App\Services\Department;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Department\DepartmentRepository;
use Exception;

class DepartmentServiceImplement extends ServiceApi implements DepartmentService{

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

    public function __construct(DepartmentRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function getDepartments()
    {
        try {
            return $this->mainRepository->getAllDepartments();
        } catch (Exception $e) {
            // Handle exception
            throw new Exception('Error fetching departments: ' . $e->getMessage());
        }
    }

    // Define your custom methods :)
}
