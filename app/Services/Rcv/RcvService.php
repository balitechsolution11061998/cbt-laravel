<?php

namespace App\Services\Rcv;

use LaravelEasyRepository\BaseService;

interface RcvService extends BaseService{

    // Write something awesome :)
    public function countDataRcv($filterDate, $filterSupplier);
    public function countDataRcvPerDays($filterDate, $filterSupplier);
}
