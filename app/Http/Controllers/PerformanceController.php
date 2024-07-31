<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerformanceController extends Controller
{
    //
    public function getPerformanceData(Request $request)
    {
        $data = DB::table("query_performance_logs")
            ->whereBetween('created_at', [now()->startOfHour(), now()->endOfHour()])
            ->get();

        return response()->json($data);
    }

}
