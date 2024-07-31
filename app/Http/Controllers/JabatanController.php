<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Traits\LogsQueryPerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class JabatanController extends Controller
{
    use LogsQueryPerformance;

    public function data(Request $request)
    {
        try {
            $searchTerm = $request->input('q', '');
            $page = $request->input('page', 1);
            $pageSize = 10;
            $jabatanId = $request->input('jabatan_id', '');
            // Cache key based on search term, provinsi_id, and page number
            $cacheKey = 'jabatan_' . md5($searchTerm . '_jabatan_' . $jabatanId . '_page_' . $page);

            // Measure query execution time and memory usage
            $startTime = microtime(true);
            $startMemory = memory_get_usage();

            // Check if data is cached
            $jabatanData = Cache::remember($cacheKey, 1, function () use ($searchTerm, $page, $pageSize) {
                // Fetch kabupaten based on search term and provinsi_id
                $query = Jabatan::query();


                // Paginate the results
                $jabatan = $query->paginate($pageSize, ['*'], 'page', $page);

                return [
                    'items' => $jabatan->items(),
                    'total_count' => $jabatan->total()
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
