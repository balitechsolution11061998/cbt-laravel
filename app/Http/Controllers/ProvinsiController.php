<?php
namespace App\Http\Controllers;

use App\Models\Province;
use App\Traits\LogsQueryPerformance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProvinsiController extends Controller
{
    use LogsQueryPerformance;

    public function data(Request $request)
    {
        try {
            $searchTerm = $request->input('q', '');
            $page = $request->input('page', 1);
            $pageSize = 10;

            // Cache key based on search term and page number
            $cacheKey = 'provinces_' . md5($searchTerm . '_page_' . $page);

            // Measure query execution time and memory usage
            $startTime = microtime(true);
            $startMemory = memory_get_usage();

            // Check if data is cached
            $provincesData = Cache::remember($cacheKey, 1, function () use ($searchTerm, $page, $pageSize) {
                // Fetch provinces based on search term
                $query = Province::query()->select('id', 'name'); // Select only necessary columns

                if ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%');
                }

                // Paginate the results
                $provinces = $query->paginate($pageSize, ['*'], 'page', $page);

                return [
                    'items' => $provinces->items(),
                    'total_count' => $provinces->total()
                ];
            });

            $executionTime = microtime(true) - $startTime;
            $memoryUsage = memory_get_usage() - $startMemory;

            // Log query performance
            $this->logQueryPerformance('province', json_encode($request->all()), $executionTime, $memoryUsage);

            return response()->json($provincesData);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'An error occurred while fetching the data.'
            ], 500);
        }
    }
}
