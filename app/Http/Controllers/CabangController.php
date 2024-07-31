<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Traits\LogsQueryPerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CabangController extends Controller
{
    use LogsQueryPerformance;
    public function count() {
        $count = Cabang::count();
        return response()->json(['count' => $count]);
    }
    public function data(Request $request)
    {
        try {
            $searchTerm = $request->input('q', '');
            $page = $request->input('page', 1);
            $pageSize = 10;
            $cabangId = $request->input('cabang_id', '');
            // Cache key based on search term, provinsi_id, and page number
            $cacheKey = 'cabang_' . md5($searchTerm . '_cabang' . $cabangId . '_page_' . $page);

            // Measure query execution time and memory usage
            $startTime = microtime(true);
            $startMemory = memory_get_usage();

            // Check if data is cached
            $jabatanData = Cache::remember($cacheKey, 1, function () use ($searchTerm, $page, $pageSize) {
                // Fetch kabupaten based on search term and provinsi_id
                $query = Cabang::query();


                // Paginate the results
                $cabang = $query->paginate($pageSize, ['*'], 'page', $page);

                return [
                    'items' => $cabang->items(),
                    'total_count' => $cabang->total()
                ];
            });

            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log query performance
            $this->logQueryPerformance('jabatan', json_encode($request->all()), $executionTime, $memoryUsage);

            return response()->json($jabatanData);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'An error occurred while fetching the data.'.$th->getMessage()
            ], 500);
        }
    }
}
