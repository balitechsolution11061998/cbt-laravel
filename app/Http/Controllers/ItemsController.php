<?php

namespace App\Http\Controllers;

use App\Events\PerformanceDataUpdated;
use App\Models\Items;
use App\Models\QueryLog;
use App\Services\Item\ItemService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ItemsController extends Controller
{
    //

    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(){
        return view('items.index');
    }

    public function data(Request $request)
    {
        try {
            if ($request->ajax()) {
                return $this->itemService->data($request);
            }
        } catch (\Exception $e) {
            $userIp = $request->ip();
            $this->logError('An error occurred while accessing the create page', $e, $userIp);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function data(Request $request)
    // {
    //     try {
    //         // Get the user's IP address
    //         $userIp = $request->ip();

    //         // Log the activity of accessing the create page using the trait
    //         $this->logActivity('Accessed item supplier data', 'User accessed the item page', $userIp);

    //         if ($request->ajax()) {
    //             // Start measuring query execution time
    //             $startTime = microtime(true);
    //             $startMemory = memory_get_usage();

    //                  // Array to store executed queries
    //         $executedQueries = [];

    //             // Listen for query events and store in $executedQueries
    //             DB::listen(function ($query) use (&$executedQueries) {
    //                 $executedQueries[] = [
    //                     'sql' => $query->sql,
    //                     'bindings' => $query->bindings,
    //                     'time' => $query->time,
    //                 ];
    //             });

    //             $data = Items::latest();

    //             // Check if search parameter is provided
    //             if ($request->search != null) {
    //                 $searchTerm = $request->search;
    //                 $data->where(function($query) use ($searchTerm) {
    //                     $query->where('supplier', 'like', '%'.$searchTerm.'%')
    //                           ->orWhere('sku', 'like', '%'.$searchTerm.'%');
    //                 });
    //             }

    //             // Use chunk to process data in chunks
    //             $results = [];
    //             $data->chunk(100, function($items) use (&$results) {
    //                 foreach ($items as $item) {
    //                     $results[] = $item;
    //                 }
    //             });

    //             foreach ($executedQueries as $query) {
    //                 QueryLog::create([
    //                     'sql' => $query['sql'],
    //                     'bindings' => json_encode($query['bindings']),
    //                     'time' => $query['time'],
    //                 ]);
    //             }

    //             // Calculate query execution time
    //             $endTime = microtime(true);
    //             $endMemory = memory_get_usage();

    //             $executionTime = $endTime - $startTime;
    //             $executionTimeInSeconds = round($executionTime, 4);
    //             $memoryUsage = $endMemory - $startMemory;

    //             $this->logQueryPerformance('items_data', $request->search, $executionTimeInSeconds, $memoryUsage, $userIp);

    //             event(new PerformanceDataUpdated);

    //             return DataTables::of($results)
    //                 ->addIndexColumn()
    //                 ->addColumn('action', function ($row) {
    //                     $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
    //                     $btn .= '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
    //                     return $btn;
    //                 })
    //                 ->rawColumns(['action'])
    //                 ->make(true);
    //         }
    //     } catch (\Exception $e) {
    //         // Log the error
    //         $this->logError('An error occurred while accessing the create page', $e, $userIp);

    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function getDataItemSupplierBySupplier(Request $request){
        if(Auth::user()->hasRole('superadministrator')){
            $data = Items::where('supplier', '111095')->get()->toArray();
        }else{
            $data = Items::where('supplier', Auth::user()->username)->get()->toArray();
        }
        return response()->json(['data' => $data]);
    }





    public function convertMemoryUsage($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        return number_format($bytes / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
    }

}
