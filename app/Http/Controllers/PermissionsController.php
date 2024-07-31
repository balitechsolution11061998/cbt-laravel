<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseJson;
use App\Services\Permissions\PermissionsService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionsController extends Controller
{
    //
    protected $permissionsService;

    public function __construct(
        PermissionsService $permissionsService,
    ) {

        $this->permissionsService = $permissionsService;
        $this->middleware('auth');

    }

    public function index(){
        return view('permissions.index');
    }

    public function data(Request $request)
    {
        try {
            // Get search query from request
            $search = $request->search;

            $permissions = $this->permissionsService->data($search);

            return DataTables::of($permissions)
                ->addColumn('action', function ($permission) {
                    return '<button class="btn btn-primary">Edit</button>';
                })
                ->rawColumns(['action'])
                ->toJson();
        } catch (Exception $e) {
            // Handle the exception as needed, for example:
            return response()->json(['error' => 'An error occurred while fetching data.'], 500);
        }
    }

    public function getAllPermissions(){
        try {
            $result = $this->permissionsService->getAllPermissions();
            return ResponseJson::response("Load data permissions success", 'Success', $result, 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }

    public function getPermissionsByRole(Request $request)
    {
        try {
            $roleId = $request->input('role_id');
            $permissions = $this->permissionsService->getByRoleId($roleId);
            return ResponseJson::response('Success load permissions in this role', 'Success', $permissions, 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }

    public function submitToRole(Request $request){
        try {
            $roleId = $request->input('role_id');
            $permissions = $request->input('permissions');
            $role = $this->permissionsService->assignPermissionsToRole($roleId, $permissions);
            return ResponseJson::response('Permissions assigned successfully', 'Success', [], 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }

    public function edit($id){
        return $this->permissionsService->edit($id);
    }

    public function store(Request $request)
    {
        try {
            $result = $this->permissionsService->createOrUpdatePermission($request->all());
            return ResponseJson::response($result['message'], 'Success', [], 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }

    // public function getAllPermissions(){
    //     try {
    //         $result = $this->permissionService->getAllPermissions();
    //         return ResponseJson::response("Load data permissions success", 'Success', $result, 200);
    //     } catch (\Exception $e) {
    //         return ResponseJson::response($e->getMessage(), 'Error', [], 500);
    //     }    }

    public function delete(Request $request){
        try {
            $id = $request->input('id');
            $this->permissionsService->delete($id);
            return ResponseJson::response('Permission deleted successfully', 'Success', [], 200);
        } catch (\Exception $e) {
            return ResponseJson::response($e->getMessage(), 'Error', [], 500);
        }
    }

    // public function submitToRole(Request $request){
    //     try {
    //         $roleId = $request->input('role_id');
    //         $permissions = $request->input('permissions');
    //         $role = $this->permissionService->assignPermissionsToRole($roleId, $permissions);
    //         return ResponseJson::response('Permissions assigned successfully', 'Success', [], 200);
    //     } catch (\Exception $e) {
    //         return ResponseJson::response($e->getMessage(), 'Error', [], 500);
    //     }
    // }



    // public function getPermissionsByRole(Request $request)
    // {
    //     try {
    //         $roleId = $request->input('role_id');
    //         $permissions = $this->permissionService->getByRoleId($roleId);
    //         return ResponseJson::response('Success load permissions in this role', 'Success', $permissions, 200);
    //     } catch (\Exception $e) {
    //         return ResponseJson::response($e->getMessage(), 'Error', [], 500);
    //     }
    // }
}
