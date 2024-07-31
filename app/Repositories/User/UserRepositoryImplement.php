<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;

class UserRepositoryImplement extends Eloquent implements UserRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    // public function create(array $data)
    // {
    //     return User::updateOrCreate(
    //         ['email' => $data['email']],
    //         $data
    //     );
    // }

    public function getAllUserWithSearch($search = null){
        $query = User::query();

        // Apply search filter if provided
        if ($search) {
            $query->orWhere('username', 'like', '%' . $search . '%');
            $query->orWhere('name', 'like', '%' . $search . '%');
        }

        // Eager load the roles relationship to avoid N+1 queries
        $users = $query->with('roles')->get();

        return $users;
    }
    public function findUserById($id)
    {
        return User::with('settings_user')->findOrFail($id);
    }

    public function deleteById($id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    public function getUserWithRelationships()
    {
        return User::with('jabatan', 'department', 'cabang')->latest();
    }
}
