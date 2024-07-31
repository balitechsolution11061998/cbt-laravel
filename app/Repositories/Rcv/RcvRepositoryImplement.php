<?php

namespace App\Repositories\Rcv;

use App\Models\OrdHead;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Rcv;
use App\Models\RcvHead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RcvRepositoryImplement extends Eloquent implements RcvRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(RcvHead $model)
    {
        $this->model = $model;
    }

    public function countDataRcv($filterDate, $filterSupplier) {
        if ($filterDate == "null") {
            $filterDate = date('Y-m');
            $filterYear = date('Y', strtotime($filterDate));
            $filterMonth = date('m', strtotime($filterDate));
        }else{
            $filterYear = date('Y', strtotime($filterDate));
            $filterMonth = date('m', strtotime($filterDate));
        }

        $supplierUser = Auth::user();

        $dailyCountsQuery = RcvHead::join('rcvdetail', 'rcvhead.id', '=', 'rcvdetail.rcvhead_id')
        ->select([
            DB::raw('DATE(receive_date) as tanggal'),
            DB::raw('COUNT(DISTINCT rcvhead.id) as jumlah'),
            DB::raw('SUM(rcvdetail.unit_cost * rcvdetail.qty_received + rcvdetail.vat_cost * rcvdetail.qty_received) as total_cost'),
        ])
        ->whereYear('receive_date', '=', $filterYear)
        ->whereMonth('receive_date', '=', $filterMonth)
        ->groupBy('tanggal')
        ->when($supplierUser->hasRole('supplier'), function ($query) use ($supplierUser) {
            $query->where('rcvhead.supplier', $supplierUser->username);
        })
        ->when(!empty($filterSupplier), function ($query) use ($filterSupplier) {
            $query->whereIn('rcvhead.supplier', (array) $filterSupplier);
        });

        $dailyCounts = $dailyCountsQuery->get();

        $totals = $dailyCounts->reduce(function ($carry, $item) {
            $carry['totalRcv'] += $item->jumlah;
            $carry['totalCostRcv'] += $item->total_cost;
            return $carry;
        }, ['totalRcv' => 0, 'totalCostRcv' => 0]);

        return [
            'totalRcv' => $totals['totalRcv'],
            'totalCostRcv' => $totals['totalCostRcv'],
        ];
    }


    public function countDataRcvPerDays($filterDate, $filterSupplier) {
        if ($filterDate == "null") {
            $filterDate = date('Y-m');
            $filterYear = date('Y', strtotime($filterDate));
            $filterMonth = date('m', strtotime($filterDate));
        } else {
            $filterYear = date('Y', strtotime($filterDate));
            $filterMonth = date('m', strtotime($filterDate));
        }

        $supplierUser = Auth::user();

        $dailyCountsQuery = RcvHead::join('rcvdetail', 'rcvhead.id', '=', 'rcvdetail.rcvhead_id')
            ->select([
                DB::raw('DATE(receive_date) as tanggal'),
                DB::raw('COUNT(DISTINCT rcvhead.id) as jumlah'),
                DB::raw('SUM(rcvdetail.unit_cost * rcvdetail.qty_received + rcvdetail.vat_cost * rcvdetail.qty_received) as total_cost'),
            ])
            ->whereYear('receive_date', '=', $filterYear)
            ->whereMonth('receive_date', '=', $filterMonth)
            ->groupBy('tanggal')
            ->when($supplierUser->hasRole('supplier'), function ($query) use ($supplierUser) {
                $query->where('rcvhead.supplier', $supplierUser->username);
            })
            ->when(!empty($filterSupplier), function ($query) use ($filterSupplier) {
                $query->whereIn('rcvhead.supplier', (array) $filterSupplier);
            });

        $dailyCounts = $dailyCountsQuery->get();

        $totals = $dailyCounts->reduce(function ($carry, $item) {
            $carry['totalRcv'] += $item->jumlah;
            $carry['totalCostRcv'] += $item->total_cost;
            return $carry;
        }, ['totalRcv' => 0, 'totalCostRcv' => 0]);

        return [

            'dailyCounts' => $dailyCounts->map(function ($item) {
                return [
                    'tanggal' => $item->tanggal,
                    'totalRcv' => $item->jumlah,
                    'totalCostRcv' => $item->total_cost,
                ];
            }),
        ];
    }

}
