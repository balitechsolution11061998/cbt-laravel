<?php

namespace App\Repositories\Permissions;

use App\Models\Permission;
use LaravelEasyRepository\Implementations\Eloquent;

class PermissionsRepositoryImplement extends Eloquent implements PermissionsRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)

    public function data($search)
    {
        $query = $this->model->query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->get();
    }

    public function getAllPermissions()
    {
        return $this->model->all()->toArray();
    }

    public function edit($id)
    {
        return $this->model->where('id',$id)->first();
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function update($id, $data)
    {
        $permission = $this->model->findOrFail($id);
        $permission->update($data);
        return $permission;
    }

    public function delete($id)
    {
        $permission = $this->model->findOrFail($id);
        return $permission->delete();
    }

    public function getByRoleId($roleId)
    {
        return $this->model->whereHas('roles', function ($query) use ($roleId) {
            $query->where('id', $roleId);
        })->get()->toArray();
    }


}
