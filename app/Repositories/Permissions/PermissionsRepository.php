<?php

namespace App\Repositories\Permissions;

use LaravelEasyRepository\Repository;

interface PermissionsRepository extends Repository{

    // Write something awesome :)
    public function data($search);
    public function create($data);
    public function edit($id);
    public function update($id,$data);
    public function delete($id);
    public function getAllPermissions();
    public function getByRoleId($id);
}
