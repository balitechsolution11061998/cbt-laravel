<?php

namespace App\Services\Rcv;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Rcv\RcvRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class RcvServiceImplement extends ServiceApi implements RcvService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
     protected $title = "";
     /**
     * uncomment this to override the default message
     * protected $create_message = "";
     * protected $update_message = "";
     * protected $delete_message = "";
     */

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(RcvRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
    public function countDataRcv($filterDate, $filterSupplier)
    {
        try {
            return $this->mainRepository->countDataRcv($filterDate, $filterSupplier);
        } catch (Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    public function countDataRcvPerDays($filterDate,$filterSupplier){
        try {
            return $this->mainRepository->countDataRcvPerDays($filterDate, $filterSupplier);
        } catch (Exception $exception) {
            Log::error($exception);
            return null;
        }
    }
}
