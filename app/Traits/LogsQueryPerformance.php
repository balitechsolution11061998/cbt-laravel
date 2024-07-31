<?php
namespace App\Traits;

use App\Models\QueryPerformanceLog;

trait LogsQueryPerformance
{
    protected function logQueryPerformance($functionName, $parameters, $executionTime, $memoryUsage)
    {
        QueryPerformanceLog::create([
            'function_name' => $functionName,
            'parameters' => $parameters,
            'execution_time' => $executionTime,
            'memory_usage' => $memoryUsage,
        ]);
    }
}
