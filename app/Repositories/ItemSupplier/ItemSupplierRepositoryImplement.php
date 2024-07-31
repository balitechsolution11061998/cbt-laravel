<?php

namespace App\Repositories\ItemSupplier;

use App\Models\Items;
use LaravelEasyRepository\Implementations\Eloquent;

class ItemSupplierRepositoryImplement extends Eloquent implements ItemSupplierRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Items $model)
    {
        $this->model = $model;
    }

    public function data()
    {
        return $this->model->latest();
    }
    // Write something awesome :)
}
