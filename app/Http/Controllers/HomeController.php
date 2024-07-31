<?php

namespace App\Http\Controllers;

use App\Models\QueryPerformanceLog;
use App\Services\Order\OrderService;
use App\Services\Rcv\RcvService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HomeController extends Controller
{
    //

    protected $orderService;
    protected $rcvService;

    public function __construct(
        OrderService $orderService,
        RcvService $rcvService,
    ) {

        $this->orderService = $orderService;
        $this->rcvService = $rcvService;
        $this->middleware('auth');

    }
    public function index(){

        return view('home');
    }

    public function countDataPoPerDays(Request $request){
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        try {
            $filterDate = $request->filterDate;
            $filterSupplier = $request->filterSupplier;
            $data = $this->orderService->countDataPoPerDays($filterDate, $filterSupplier);

            // Calculate execution time and memory usage
            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log performance metrics
            QueryPerformanceLog::create([
                'function_name' => 'countDataPo',
                'parameters' => json_encode(['filterDate' => $filterDate, 'filterSupplier' => $filterSupplier]),
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);

            return response()->json([
                'success' => true,
                'data' => $data,
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function countDataPo(Request $request) {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        try {
            $filterDate = $request->filterDate;
            $filterSupplier = $request->filterSupplier;
            $total = $this->orderService->countDataPo($filterDate, $filterSupplier);

            // Calculate execution time and memory usage
            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log performance metrics
            QueryPerformanceLog::create([
                'function_name' => 'countDataPo',
                'parameters' => json_encode(['filterDate' => $filterDate, 'filterSupplier' => $filterSupplier]),
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);

            return response()->json([
                'success' => true,
                'total' => $total,
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function countDataRcv(Request $request) {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        try {
            $filterDate = $request->filterDate;
            $filterSupplier = $request->filterSupplier;
            $total = $this->rcvService->countDataRcv($filterDate, $filterSupplier);

            // Calculate execution time and memory usage
            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log performance metrics
            QueryPerformanceLog::create([
                'function_name' => 'countDataRcv',
                'parameters' => json_encode(['filterDate' => $filterDate, 'filterSupplier' => $filterSupplier]),
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);

            return response()->json([
                'success' => true,
                'total' => $total,
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function countDataRcvPerDays(Request $request){
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        try {
            $filterDate = $request->filterDate;
            $filterSupplier = $request->filterSupplier;
            $total = $this->rcvService->countDataRcvPerDays($filterDate, $filterSupplier);

            // Calculate execution time and memory usage
            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log performance metrics
            QueryPerformanceLog::create([
                'function_name' => 'countDataPo',
                'parameters' => json_encode(['filterDate' => $filterDate, 'filterSupplier' => $filterSupplier]),
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);

            return response()->json([
                'success' => true,
                'total' => $total,
                'execution_time' => $executionTime,
                'memory_usage' => $memoryUsage
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }

}
