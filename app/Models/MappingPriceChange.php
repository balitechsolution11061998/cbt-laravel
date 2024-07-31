<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MappingPriceChange extends Model
{
    use HasFactory;
    protected $table = 'mapping_app_pricelist';
    public $guarded = [];


    public function roles()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
