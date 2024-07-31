<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryApprovalCostChange extends Model
{
    use HasFactory;
    protected $table = 'history_app_pricelist';
    public $guarded = [];
}
