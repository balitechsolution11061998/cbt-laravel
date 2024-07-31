<?php

namespace App\Services\Role;

use App\Helpers\ResponseJson;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepository;

class RoleServiceImplement extends ServiceApi implements RoleService{

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
     protected $userRepository;

    public function __construct(RoleRepository $mainRepository,UserRepository $userRepository)
    {
      $this->mainRepository = $mainRepository;
      $this->userRepository = $userRepository;
    }

    public function getAllRoles()
    {
        return $this->mainRepository->getAllRoles();
    }

    public function getAllRolesWithSearch($search = null)
    {
        return $this->mainRepository->getAllRolesWithSearch($search);
    }

    public function getRolesById($id)
    {
        try {
            $permission = $this->mainRepository->findRolesById($id);
            $permissionData = $permission->toArray();
            return ResponseJson::response('Success', 'Roles Found', $permissionData, 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Roles data is not available', [], 500);
        }
    }

    public function createOrUpdateRoles($requestData)
    {
        try {
            // Validate the incoming request data
            $validatedData = validator($requestData, [
                'name' => 'required|string|unique:roles,name,' . ($requestData['id'] ?: 'NULL') . ',id',
                'display_name' => 'required|string',
                'description' => 'required|string',
            ])->validate();

            // Check if ID is provided, if so, update the existing permission
            if ($requestData['id']) {
                $permission = $this->mainRepository->update($requestData['id'], $validatedData);
                $message = 'Roles updated successfully';
            } else {
                // Create a new permission if ID is not provided
                $permission = $this->mainRepository->create($validatedData);
                $message = 'Roles created successfully';
            }

            return ['message' => $message, 'roles' => $permission];
        } catch (\Exception $e) {
            throw new \Exception('Error: ' . $e->getMessage());
        }
    }

    public function deleteRoles($id)
    {
        try {
            $this->mainRepository->deleteById($id);
            return ['message' => 'Roles deleted successfully'];
        } catch (\Exception $e) {
            throw new \Exception('Error: ' . $e->getMessage());
        }
    }
    public function assignRoleToUser($roleId, $roles)
    {
        $user =  $this->userRepository->findUserById($roleId);
        $user->syncRoles($roles);
        return $user;
    }
    public function getByUserId($userId)
    {
        return $this->mainRepository->getByUserId($userId);
    }

    // Define your custom methods :)
}
