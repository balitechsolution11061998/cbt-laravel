<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceChange extends Model
{
    use HasFactory;
    protected $table = 'pricelist_head';

    public $guarded = [];
    public function priceListDetails()
    {
        return $this->hasMany(PriceChangeDetail::class, 'pricelist_head_id', 'id');
    }

    public function suppliers()
    {
        return $this->hasOne(Supplier::class, 'supp_code', 'supplier_id');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'username', 'approval_id');
    }

    public function history(){
        return $this->hasMany(HistoryApprovalCostChange::class, 'pricelist_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($priceChange) {
            if (is_null($priceChange->pricelist_no)) {
                $priceChange->pricelist_no = 'PL00' . $priceChange->id;
                $priceChange->saveQuietly();
            }
        });
    }
}
