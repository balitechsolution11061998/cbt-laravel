<?php

namespace App\Services\Item;

use App\Events\PerformanceDataUpdated;
use LaravelEasyRepository\ServiceApi;
use App\Repositories\Item\ItemRepository;
use App\Repositories\ItemSupplier\ItemSupplierRepository;
use App\Traits\QueryLoggingTrait;
use App\Traits\LogsActivity;
use App\Traits\QueryPerformanceLoggingTrait;
use Yajra\DataTables\DataTables;

class ItemServiceImplement extends ServiceApi implements ItemService
{
    use QueryLoggingTrait, LogsActivity, QueryPerformanceLoggingTrait;

    /**
     * set title message api for CRUD
     * @param string $title
     */
    protected $title = "";
    /**
     * uncomment this to override the default message
     * protected $create_message = "";
     * protected $update_message = "";
     * protected $delete_message = "";
     */

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(ItemSupplierRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }


    public function data($request)
    {
        $userIp = $request->ip();

        // Log activity
        $this->logActivity('Accessed item supplier data', 'User accessed the item page', $userIp);

        $executedQueries = $this->logQueries();
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        $data = $this->mainRepository->data();


        // Apply search filter
        if ($request->search != null) {
            $searchTerm = $request->search;
            $data->where(function ($query) use ($searchTerm) {
                $query->where('supplier', 'like', '%' . $searchTerm . '%')
                    ->orWhere('sku', 'like', '%' . $searchTerm . '%');
            });
        }

        // Process data in chunks
        $results = [];
        $data->chunk(100, function ($items) use (&$results) {
            foreach ($items as $item) {
                $results[] = $item;
            }
        });


        $endTime = microtime(true);
        $endMemory = memory_get_usage();

        $executionTime = $endTime - $startTime;
        $executionTimeInSeconds = round($executionTime, 4);
        $memoryUsage = $endMemory - $startMemory;

        $this->saveQueryLogs($executedQueries);
        $this->logQueryPerformance('items_data', $request->search, $executionTimeInSeconds, $memoryUsage, $userIp);
        event(new PerformanceDataUpdated);
        // Calculate execution time and memory usage (if needed)

        return DataTables::of($results)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                $btn .= '<a href="javascript:void(0)" class="delete btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
