<?php

namespace App\Services\User;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\User\UserRepository;

class UserServiceImplement extends ServiceApi implements UserService{

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

    public function __construct(UserRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }
    public function getData($request = [])
    {
        $query = $this->mainRepository->getUserWithRelationships();

        if ($request['name']) {
            $query->where('name', 'like', '%' . $request['name'] . '%');
        }

        if ($request['department']) {
            $query->whereHas('department', function ($q) use ($request) {
                $q->where('id',  $request['department']);
            });
        }

        if ($request['cabang']) {
            $query->whereHas('cabang', function ($q) use ($request) {
                $q->where('id',  $request['cabang']);
            });
        }

        return $query->get();
    }
}
