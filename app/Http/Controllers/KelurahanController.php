<?php

namespace App\Http\Controllers;

use App\Models\Kelurahan;
use App\Traits\LogsQueryPerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KelurahanController extends Controller
{
    use LogsQueryPerformance;

    public function data(Request $request)
    {
        try {
            $searchTerm = $request->input('q', '');
            $page = $request->input('page', 1);
            $pageSize = 10;
            $kecamatanId = $request->input('kecamatan_id', '');
            // Cache key based on search term, provinsi_id, and page number
            $cacheKey = 'kelurahan_' . md5($searchTerm . '_kecamatan_' . $kecamatanId . '_page_' . $page);

            // Measure query execution time and memory usage
            $startTime = microtime(true);
            $startMemory = memory_get_usage();

            // Check if data is cached
            $kelurahan = Cache::remember($cacheKey, 1, function () use ($searchTerm, $page, $pageSize, $kecamatanId) {
                // Fetch kabupaten based on search term and provinsi_id
                $query = Kelurahan::query()->select('id', 'name')->where('kecamatan_id', $kecamatanId);

                if ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                }

                // Paginate the results
                $kabupaten = $query->paginate($pageSize, ['*'], 'page', $page);

                return [
                    'items' => $kabupaten->items(),
                    'total_count' => $kabupaten->total()
                ];
            });

            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log query performance
            $this->logQueryPerformance('kelurahan', json_encode($request->all()), $executionTime, $memoryUsage);

            return response()->json($kelurahan);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'An error occurred while fetching the data.'.$th->getMessage()
            ], 500);
        }
    }
}
