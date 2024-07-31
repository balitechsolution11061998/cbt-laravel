<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\Department\DepartmentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    //
    protected $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(){
        return view('departments.index');
    }

    public function data(): JsonResponse
    {
        try {
            $departments = $this->departmentService->getDepartments();
            return response()->json($departments);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->departmentService->getDepartments();
            return DataTables::of($data)
                ->addColumn('action', function($row) {
                    $editBtn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" onclick="editDepartment('.$row->id.')"><i class="fas fa-edit"></i></a>';
                    $deleteBtn = ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" onclick="deleteDepartment('.$row->id.')"><i class="fas fa-trash-alt"></i></a>';
                    return $editBtn . $deleteBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function count() {
        $count = Department::count();
        return response()->json(['count' => $count]);
    }

    public function store(Request $request)
    {
        $uniqueRule = $request->id ? 'unique:departments,kode_department,' . $request->id : 'unique:departments,kode_department';

        $request->validate([
            'kode_department' => ['required', 'min:3', $uniqueRule],
            'name' => ['required', 'min:3', 'unique:departments,name' . ($request->id ? ',' . $request->id : '')],
            'descriptions' => ['required', 'min:5'],
        ]);

        try {
            if ($request->id) {
                // Update existing department
                $department = Department::findOrFail($request->id);
                $department->update($request->all());
                $message = 'Department updated successfully.';
            } else {
                // Create new department
                Department::create($request->all());
                $message = 'Department added successfully.';
            }

            return response()->json(['success' => $message]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $department = Department::findOrFail($id);
            return response()->json($department);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->delete();
            return response()->json(['success' => 'Department deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }


}
