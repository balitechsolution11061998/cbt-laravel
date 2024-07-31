<?php

namespace App\Services\Order;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Order\OrderRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderServiceImplement extends ServiceApi implements OrderService{

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

    public function __construct(OrderRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function countDataPo($filterDate, $filterSupplier)
    {
        try {
            return $this->mainRepository->countDataPo($filterDate, $filterSupplier);
        } catch (Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    public function countDataPoPerDays($filterDate,$filterSupplier){
        try {
            return $this->mainRepository->countDataPoPerDays($filterDate, $filterSupplier);
        } catch (Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    public function data($filterDate,$filterSupplier){
        try {
            return $this->mainRepository->data($filterDate, $filterSupplier);
        } catch (Exception $exception) {
            Log::error($exception);
            return null;
        }
    }

    // Define your custom methods :)
}
