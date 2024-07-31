<?php

namespace App\Http\Controllers;

use App\Models\MappingPriceChange;
use Illuminate\Http\Request;
use App\Traits\LogsActivity;
use App\Traits\QueryPerformanceLoggingTrait;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SettingsController extends Controller
{
    //
    use LogsActivity, QueryPerformanceLoggingTrait;

    public function priceChange(){
        return view('settings.index');
    }

    public function approvalPriceChangeData(Request $request)
    {
        try {
            // Capture and log the incoming request data
            $data = MappingPriceChange::with([
                'roles' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->select('id', 'role_id', 'position', 'region_id', 'created_at', 'updated_at')->get();


            return DataTables::of($data)
            ->addIndexColumn()

            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                $btn .= '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
        } catch (\Exception $e) {
            dd($e->getMessage());

            // Return an error response
            return response()->json(['success' => false, 'message' => 'An error occurred while processing the data.'], 500);
        }
    }

    public function priceChangeStore(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'role_id' => 'required',
            'position' => 'required',
            'region_id' => 'required',
            // Add other validation rules as needed
        ]);

        try {
            // Check if a record with the same role_id, position, and region_id already exists
            $existingRecord = MappingPriceChange::where('role_id', $validatedData['role_id'])
                                                 ->where('position', $validatedData['position'])
                                                 ->where('region_id', $validatedData['region_id'])
                                                 ->first();

            if ($existingRecord) {
                // Return a response indicating the record already exists
                return response()->json(['success' => false, 'message' => 'A price change with the same role, position, and region already exists.'], 409);
            }

            // Create and save the new role
            $mappingPricechange = new MappingPriceChange();
            $mappingPricechange->role_id = $validatedData['role_id'];
            $mappingPricechange->position = $validatedData['position'];
            $mappingPricechange->region_id = $validatedData['region_id'];
            $mappingPricechange->save();

            // Log the success activity (if using a logging trait)
            $this->logActivity('Mapping Cost Change Created', 'A new price list approval was created: ' . $mappingPricechange->role_id, $request->ip());

            // Return a success response
            return response()->json(['success' => true, 'message' => 'Price list created successfully.'], 201);

        } catch (\Exception $e) {
            // Log the exception (you might want to remove dd and use a proper logging mechanism)
            // Log::error($e->getMessage());

            // Return an error response
            return response()->json(['success' => false, 'message' => 'An error occurred while creating the price change.'], 500);
        }
    }

}
