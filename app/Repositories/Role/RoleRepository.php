<?php

namespace App\Repositories\Role;

use LaravelEasyRepository\Repository;

interface RoleRepository extends Repository{
    public function create($data);
    public function update($id,$data);
    public function getAllRoles();
    public function findRolesById($id);
    public function deleteById($id);
    public function getAllRolesWithSearch($search);
    public function getByUserId($user_id);
}
