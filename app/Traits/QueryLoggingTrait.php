<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\QueryLog;

trait QueryLoggingTrait
{
    protected function logQueries()
    {
        $executedQueries = [];

        DB::listen(function ($query) use (&$executedQueries) {
            $executedQueries[] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ];
        });

        return $executedQueries;
    }

    protected function saveQueryLogs(array $executedQueries)
    {
        foreach ($executedQueries as $query) {
            QueryLog::create([
                'sql' => $query['sql'],
                'bindings' => json_encode($query['bindings']),
                'time' => $query['time'],
            ]);
        }
    }
}
