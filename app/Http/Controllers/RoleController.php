<?php

namespace App\Http\Controllers;

use App\Services\RolesService;
use Illuminate\Http\Request;
use App\Helpers\ResponseJson;
use App\Services\Role\RoleService;
use Illuminate\Support\Facades\Cache;
use DataTables;

class RoleController extends Controller
{
    //
    private $rolesService;
    public function __construct(RoleService $rolesService)
    {
        $this->rolesService = $rolesService;
    }
    public function index(){
        return view('roles.index');
    }
    public function store(Request $request)
    {
        try {
            $result = $this->rolesService->createOrUpdateRoles($request->all());
            return ResponseJson::response($result['message'], 'Success', [], 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }
    public function data(Request $request)
    {
        $search = $request->search;
        $roles = $this->rolesService->getAllRolesWithSearch($search);

        return DataTables::of($roles)
            ->addColumn('action', function ($permission) {
                return '<button class="btn btn-primary">Edit</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function edit($id){
        return $this->rolesService->getRolesById($id);
    }

    public function delete(Request $request){
        try {
            $id = $request->input('id');
            $this->rolesService->deleteRoles($id);
            return ResponseJson::response('Roles deleted successfully', 'Success', [], 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }

    public function getAllRoles(){
        try {
            $result = $this->rolesService->getAllRoles();
            return ResponseJson::response("Load data roles success", 'Success', $result, 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }
    public function submitRolesToUser(Request $request){
        try {
            $user_id = $request->input('user_id');
            $roles = $request->input('roles');
            $role = $this->rolesService->assignRoleToUser($user_id, $roles);
            return ResponseJson::response('Roles assigned successfully', 'Success', [], 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }

    public function getRolesByUser(Request $request){
        try {
            $userId = $request->input('user_id');
            $roles = $this->rolesService->getByUserId($userId);
            return ResponseJson::response('Success load roles in this user', 'Success', $roles, 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }

}
