<?php

namespace App\Repositories\Role;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Role;

class RoleRepositoryImplement extends Eloquent implements RoleRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $role = $this->model->findOrFail($id);
        $role->update($data);
        return $role;
    }

    public function getAllRoles()
    {
        return $this->model->all()->toArray();
    }

    public function findRolesById($id)
    {
        return $this->model->findOrFail($id);
    }

    public function deleteById($id)
    {
        $roles = $this->model->findOrFail($id);
        return $roles->delete();
    }

    public function getAllRolesWithSearch($search = null){
        $query = $this->model->query();

        // Apply search filter if provided
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('display_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        return $query->get();
    }

    public function getByUserId($userId)
    {
        return $this->model->whereHas('users', function ($query) use ($userId) {
            $query->where('id', $userId);
        })->get()->toArray();
    }
}
