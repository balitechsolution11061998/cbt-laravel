<?php

namespace App\Services\Permissions;

use LaravelEasyRepository\BaseService;

interface PermissionsService extends BaseService{

    // Write something awesome :)
    public function data($search);
    public function edit($id);
    public function createOrUpdatePermission($data);
    public function delete($id);
    public function getAllPermissions();
    public function getByRoleId($id);
    public function assignPermissionsToRole($roleId,$permissions);
}
