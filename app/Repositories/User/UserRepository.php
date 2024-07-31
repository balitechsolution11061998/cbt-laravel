<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository{

    public function getAllUserWithSearch($search);
    // public function create(array $data);
    public function findUserById($id);
    public function deleteById($id);
    public function getUserWithRelationships();
}
