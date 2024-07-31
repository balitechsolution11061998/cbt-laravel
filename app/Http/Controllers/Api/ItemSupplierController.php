<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ItemSupplierController extends Controller
{
    //
    public function store(Request $request)
    {
        try {
            $dataItem = '';
            $startTime = microtime(true);
            $datas = $request->all();
            foreach ($datas as $data) {

                $itemSupplier = [
                    'supplier' => $data['supplier'],
                    'sup_name' => $data['sup_name'],
                    'sku' => $data['sku'],
                    'sku_desc' => $data['sku_desc'],
                    'upc' => $data['upc'],
                    'unit_cost' => $data['unit_cost'],
                    'create_id' => $data['create_id'],
                    'create_date' => $data['create_date'],
                    'last_update_id' => $data['last_update_id'],
                    'last_update_date' => $data['last_update_date'],
                ];


                // Check if the item exists in the database
                $existingItem = DB::table('item_supplier')
                    ->where('supplier', $data['supplier'])
                    ->where('sup_name', $data['sup_name'])
                    ->where('sku', $data['sku'])
                    ->where('sku_desc', $data['sku_desc'])
                    ->where('upc', $data['upc'])
                    ->first();

                if ($existingItem) {
                    // If the item exists, update the fields without updating unit_cost
                    DB::table('item_supplier')
                        ->where('id', $existingItem->id)
                        ->update([
                            'supplier' => $data['supplier'],
                            'sup_name' => $data['sup_name'],
                            'sku' => $data['sku'],
                            'sku_desc' => $data['sku_desc'],
                            'upc' => $data['upc'],
                            'create_id' => $data['create_id'],
                            'create_date' => $data['create_date'],
                            'last_update_id' => $data['last_update_id'],
                            'last_update_date' => $data['last_update_date'],
                        ]);
                } else {
                    // If the item does not exist, insert the new item
                    DB::table('item_supplier')->insert($itemSupplier);
                }

            }

            // $executionTime = $endTime - $startTime;

            // // Dispatch the NotifikasiTelegramEvent after the foreach loop
            // $message = 'Sebanyak ' . count($datas) . ' data item supplier '.$data['sup_name'].' telah diperbaharui di sistem portal supplier pada url: ' . env('APP_URL');
            // $channel_id = env('TELEGRAM_CHANNEL_ID', '');
            // Event::dispatch(new NotifikasiTelegramEvent($message, $channel_id));


            return response()->json([
                'message' => 'Sukses insert item supplier',
                'success' => true,
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
            // DB::rollBack();
            return response()->json([
                'message' => 'Gagal insert item supplier',
                'success' => false,
            ]);
        }
    }
}
