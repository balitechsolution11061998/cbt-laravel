<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueryPerformanceLog extends Model
{
    use HasFactory;
    protected $table = 'query_performance_logs';
    public $guarded = [];
}
