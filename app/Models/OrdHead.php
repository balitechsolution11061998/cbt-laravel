<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdHead extends Model
{
    use HasFactory;
    protected $table = 'ordhead';
    public $guarded = '[]';

    public function suppliers()
    {
        return $this->hasOne(Supplier::class, 'supp_code', 'supplier');
    }

    public function ordDetail()
    {
        return $this->hasMany(OrdSku::class, 'order_no', 'order_no');
    }

    public function rcvHead()
    {
        return $this->hasOne(RcvHead::class, 'order_no', 'order_no');
    }



    public function stores()
    {
        return $this->hasOne(Store::class, 'store', 'ship_to');
    }

}
