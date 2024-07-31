<?php

namespace App\Repositories\Order;

use LaravelEasyRepository\Repository;

interface OrderRepository extends Repository{

    // Write something awesome :)
    public function countDataPo($filterDate, $filterSupplier);
    public function countDataPoPerDays($filterDate, $filterSupplier);
    public function data($filterDate, $filterSupplier);

}
