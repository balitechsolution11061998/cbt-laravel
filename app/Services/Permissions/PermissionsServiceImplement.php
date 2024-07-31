<?php

namespace App\Services\Permissions;

use App\Models\Role;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Permissions\PermissionsRepository;
use App\Repositories\Role\RoleRepository;
use Exception;

class PermissionsServiceImplement extends ServiceApi implements PermissionsService{

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

    public function __construct(PermissionsRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function data($search)
    {
        try {
            return $this->mainRepository->data($search);
        } catch (Exception $exception) {
            return null;
        }
    }


    public function edit($id)
    {
        try {
            return $this->mainRepository->edit($id);
        } catch (Exception $exception) {
            return null;
        }
    }


    public function createOrUpdatePermission($requestData)
    {
        try {
            // Validate the incoming request data
            $validatedData = validator($requestData, [
                'name' => 'required|string|unique:permissions,name,' . ($requestData['id'] ?: 'NULL') . ',id',
                'display_name' => 'required|string',
                'description' => 'required|string',
            ])->validate();

            // Check if ID is provided, if so, update the existing permission
            if ($requestData['id']) {
                $permission = $this->mainRepository->update($requestData['id'], $validatedData);
                $message = 'Permission updated successfully';
            } else {
                // Create a new permission if ID is not provided
                $permission = $this->mainRepository->create($validatedData);
                $message = 'Permission created successfully';
            }

            return ['message' => $message, 'permission' => $permission];
        } catch (\Exception $e) {
            throw new \Exception('Error: ' . $e->getMessage());
        }
    }

    public function getAllPermissions()
    {
        return $this->mainRepository->getAllPermissions();
    }

    public function getByRoleId($roleId)
    {
        return $this->mainRepository->getByRoleId($roleId);
    }


    public function assignPermissionsToRole($roleId, $permissions)
    {

        $role = Role::find($roleId);
        $role->syncPermissions($permissions);

        return $role;
    }

    public function delete($id)
    {
        try {
            $this->mainRepository->delete($id);
            return ['message' => 'Permission deleted successfully'];
        } catch (\Exception $e) {
            throw new \Exception('Error: ' . $e->getMessage());
        }
    }

}
