<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use App\Models\OrdHead;
use App\Models\RcvDetail;
use App\Models\RcvHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RcvController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $requestData = $request->all();

            $chunkSize = 100;

            collect($requestData)->chunk($chunkSize)->each(function ($chunk) {
                DB::table('temp_rcv')->insert($chunk->toArray());
            });

            $rcvNotExists = DB::table('temp_rcv')->get()->toArray();
            // Ensure $rcvNotExists is an array of objects or associative arrays
            if (!is_array($rcvNotExists)) {
                return throw new \Exception('Data retrieved from temp_rcv is not an array');
            }

            $datas = collect($rcvNotExists);

            foreach ($datas->groupBy('receive_no') as $data) {

                $totalServiceLevel = 0;
                $sub_total = 0;
                $sub_total_vat_cost = 0;
                $totalItems = count($data);

                $rcvHead = RcvHead::updateOrCreate(
                    [
                        "receive_no" => $data[0]->receive_no,
                    ],
                    [
                        "receive_date" => $data[0]->receive_date,
                        "created_date" => $data[0]->created_date,
                        "receive_id" => $data[0]->receive_id,
                        "order_no" => $data[0]->order_no,
                        "ref_no" => $data[0]->ref_no,
                        "order_type" => $data[0]->order_type,
                        "status_ind" => $data[0]->status_ind,
                        "approval_date" => $data[0]->approval_date,
                        "approval_id" => $data[0]->approval_id,
                        "store" => $data[0]->store,
                        "store_name" => $data[0]->store_name,
                        "supplier" => $data[0]->supplier,
                        "sup_name" => $data[0]->sup_name,
                        "comment_desc" => $data[0]->comment_desc,
                        // Add other columns as needed
                    ]
                );

                foreach ($data as $detail) {
                    RcvDetail::updateOrCreate(
                        [
                            'rcvhead_id' => $rcvHead->id,
                            'sku' => $detail->sku,
                            'receive_no' => $detail->receive_no,
                        ],
                        [
                            "store" => $detail->store,
                            "sku" => $detail->sku,
                            "upc" => $detail->upc,
                            "sku_desc" => $detail->sku_desc,
                            "qty_expected" => $detail->qty_expected,
                            "qty_received" => $detail->qty_received,
                            "unit_cost" => $detail->unit_cost,
                            "unit_retail" => $detail->unit_retail,
                            "vat_cost" => $detail->vat_cost,
                            "unit_cost_disc" => $detail->unit_cost_disc,
                            "service_level" => $detail->qty_received / $detail->qty_expected * 100,
                        ]
                    );

                    $totalServiceLevel += ($detail->qty_received / $detail->qty_expected) * 100;
                    $sub_total += $detail->qty_received * $detail->unit_cost;
                    $sub_total_vat_cost += $detail->vat_cost * $detail->qty_received;
                }

                $averageServiceLevel = $totalServiceLevel / $totalItems;
                $rcvHead->update([
                    'average_service_level' => $averageServiceLevel,
                    'sub_total' => $sub_total,
                    'sub_total_vat_cost' => $sub_total_vat_cost,
                ]);

                $podata =  OrdHead::where('order_no', $data[0]->order_no)->first();
                if($podata != null && $averageServiceLevel == 100){
                    $podata->update([
                        'status' => 'Completed',
                        'estimated_delivery_date' => $data[0]->receive_date,
                    ]);
                }else if($podata != null && $averageServiceLevel < 100){
                    $podata->update([
                        'status' => 'Incompleted',
                        'estimated_delivery_date' => $data[0]->receive_date,
                    ]);
                }
            }

            // Truncate table temp_rcv
            DB::table('temp_rcv')->truncate();

            return response()->json([
                'success' => true,
                'message' => 'Data inserted successfully',
            ], 201);
        } catch (\Throwable $th) {
            return $th->getMessage();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi nanti.',
                'trace' => $th->getTrace(),
            ], 409);
        }
    }



}
