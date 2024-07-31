<?php

namespace App\Services\Role;

use LaravelEasyRepository\BaseService;

interface RoleService extends BaseService{
    // Write something awesome :)
    public function createOrUpdateRoles($data);
    public function getAllRolesWithSearch($search);
    public function getRolesById($id);
    public function deleteRoles($id);
    public function getAllRoles();
    public function assignRoleToUser($id,$data);
    public function getByUserId($id);

}
