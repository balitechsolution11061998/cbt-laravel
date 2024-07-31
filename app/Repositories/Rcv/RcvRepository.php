<?php

namespace App\Repositories\Rcv;

use LaravelEasyRepository\Repository;

interface RcvRepository extends Repository{

    // Write something awesome :)
    public function countDataRcv($filterDate, $filterSupplier);
    public function countDataRcvPerDays($filterDate, $filterSupplier);
}
