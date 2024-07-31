<?php

namespace App\Http\Controllers;

use App\Models\OrdHead;
use App\Models\QueryPerformanceLog;
use App\Services\Order\OrderService;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrdHeadController extends Controller
{
    //
    protected $orderService;
    protected $rcvService;

    public function __construct(
        OrderService $orderService,
    ) {

        $this->orderService = $orderService;
        $this->middleware('auth');

    }
    public function index(){
        return view('order.index');
    }

    public function count(Request $request)
{
    $startTime = microtime(true);
    $startMemory = memory_get_usage();

    try {
        // Get filter parameters from the request
        $filterDate = $request->filterDate;
        $filterSupplier = $request->filterSupplier;

        // Use the orderService to get the query builder with filters applied
        $query = $this->orderService->countDataPo($filterDate, $filterSupplier);

        // Count total records before pagination


        // Calculate execution time and memory usage
        $executionTime = microtime(true) - $startTime;
        $memoryUsage = memory_get_usage() - $startMemory;

        // Log performance metrics
        QueryPerformanceLog::create([
            'function_name' => 'Show Data PO',
            'parameters' => json_encode(['filterDate' => $filterDate, 'filterSupplier' => $filterSupplier]),
            'execution_time' => $executionTime,
            'memory_usage' => $memoryUsage
        ]);

        return response()->json([
            'draw' => $request->input('draw'),
            'data' => $query,
        ]);
    } catch (\Throwable $th) {
        return response()->json([
            'success' => false,
            'error' => $th->getMessage()
        ], 500);
    }
}


    public function data(Request $request)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        try {
            $filterDate = $request->filterDate;
            $filterSupplier = $request->filterSupplier;
            $data = $this->orderService->data($filterDate, $filterSupplier);

            // Calculate execution time and memory usage
            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log performance metrics
            QueryPerformanceLog::create([
                'function_name' => 'Show Data PO',
                'parameters' => json_encode(['filterDate' => $filterDate, 'filterSupplier' => $filterSupplier]),
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);

            return DataTables::of($data)
                ->addColumn('actions', function($row) {
                    return '<button class="btn btn-sm btn-primary">Action</button>';
                })
                ->rawColumns(['actions'])
                ->toJson();
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function delivery(Request $request)
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        try {
            $filterDate = $request->filterDate;
            $filterSupplier = $request->filterSupplier;

            // Initialize the query with eager loading
            $query = OrdHead::with('stores')
                ->whereIn('status', ['confirmed', 'printed'])
                ->where('estimated_delivery_date', '>', now());

            // Apply filters if provided
            if ($filterDate) {
                $query->whereDate('created_at', $filterDate);
            }

            if ($filterSupplier) {
                $query->where('supplier_id', $filterSupplier);
            }

            // Execute the query and get the results
            $data = $query->get();

            // Calculate execution time and memory usage
            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log performance metrics
            QueryPerformanceLog::create([
                'function_name' => 'Show Confirmed PO',
                'parameters' => json_encode(['filterDate' => $filterDate, 'filterSupplier' => $filterSupplier]),
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);

            return DataTables::of($data)
                ->addColumn('actions', function($row) {
                    return '<button class="btn btn-sm btn-primary">Action</button>';
                })
                ->rawColumns(['actions'])
                ->toJson();
        } catch (\Throwable $th) {
            // Log the error for debugging purposes

            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing your request. Please try again later.'
            ], 500);
        }
    }


}
