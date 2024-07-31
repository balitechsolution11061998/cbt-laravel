<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Traits\LogsQueryPerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KecamatanController extends Controller
{
    use LogsQueryPerformance;

    public function data(Request $request)
    {
        try {
            $searchTerm = $request->input('q', '');
            $page = $request->input('page', 1);
            $pageSize = 10;
            $kabupatenId = $request->input('kabupaten_id', '');
            // Cache key based on search term, provinsi_id, and page number
            $cacheKey = 'kecamatan_' . md5($searchTerm . '_kabupaten_' . $kabupatenId . '_page_' . $page);

            // Measure query execution time and memory usage
            $startTime = microtime(true);
            $startMemory = memory_get_usage();

            // Check if data is cached
            $kabupatenData = Cache::remember($cacheKey, 1, function () use ($searchTerm, $page, $pageSize, $kabupatenId) {
                // Fetch kabupaten based on search term and provinsi_id
                $query = Kecamatan::query()->select('id', 'name')->where('kabupaten_id', $kabupatenId);

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
            $this->logQueryPerformance('kecamatan', json_encode($request->all()), $executionTime, $memoryUsage);

            return response()->json($kabupatenData);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'An error occurred while fetching the data.'.$th->getMessage()
            ], 500);
        }
    }
}
