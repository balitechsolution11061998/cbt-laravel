<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceChangeDetail extends Model
{
    use HasFactory;
    protected $table = 'pricelist_detail';
    public $guarded = [];
}
