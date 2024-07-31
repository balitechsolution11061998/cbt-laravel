<?php
namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Faker\Factory as Faker;

class RcvTest extends TestCase
{
    use  WithFaker;

    /**
     * A basic unit test example.
     */
    public function test_insert_order_through_api(): void
    {
        $apiEndpointRcv = 'https://application-all.test/api/rcv/store';
        $faker = Faker::create();
        $allRcv = [];

        // Set the not after date to a random date within 30 days after the current date
        $startDate = date('Y-01-01'); // First day of the current year
        $currentDate = date('Y-m-d');
        $currentDatePlus30 = date('Y-m-d', strtotime($currentDate));
        $receiveNo = 1;
        $orderNo = 1;
        // Loop through each day from the start date until 30 days from the current date
        for ($date = $startDate; $date <= $currentDatePlus30; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
            $notAfterDate = date('Y-m-d', strtotime($date));

            // Generate 2 data entries for each date
            for ($entry = 1; $entry <= 10; $entry++) {
                $orderNumbers = [];
                $randomLimit = mt_rand(1, 100);

                // Generate unique order numbers
                for ($i = 1; $i <= 20; $i++) {
                    $orderNumbers[] = ((int)str_replace('-', '', $date) * 100) + ($entry * 10) + $i;
                }

                // Randomly select 2 to 9 order numbers to duplicate
                $duplicates = $faker->randomElements($orderNumbers, mt_rand(2, 9));

                // Generate order data for each set
                for ($i = 1; $i <= 10; $i++) {
                    $sku = mt_rand(1000, 1000000); // Example: SKU_2024-01-01_1_1, SKU_2024-01-01_1_2, ...
                    $upc = mt_rand(1000, 1000000); // Example: UPC_2024-01-01_1_1, UPC_2024-01-01_1_2, ...

                    // Generate a random unit_cost between 1000 and 1000000
                    $unit_cost = mt_rand(1000, 1000000);
                    // Generate random qty_ordered between 1 and 100
                    $qty_ordered = mt_rand(1, 100);
                    // Set default discount
                    $unit_discount = 0;

                    // Check if qty_ordered is above 75 to apply discount
                    if ($qty_ordered > 75) {
                        $unit_discount = 75;
                    }

                    // Calculate vat_cost as 11% of unit_cost
                    $vat_cost = $unit_cost * 0.11;
                    $unit_retail = $unit_cost + ($unit_cost * 0.50);
                    $qty_received = mt_rand(max(0, $qty_ordered - 1), $qty_ordered + 1);

                    // Generate order data for the item
                    $orderData = [
                        'receive_no' =>  $receiveNo++, // Random receive_no
                        'receive_date' => $date,
                        'created_date' => $date,
                        'receive_id' => 'R' . str_pad($i, 5, '0', STR_PAD_LEFT),
                        'order_no' =>  $orderNo++, // Assign the unique order number
                        'ref_no' => 1,
                        'order_type' => "DS",
                        'status_ind' => '10',
                        'approval_date' => $date,
                        'approval_id' => '123',
                        'store' => 40,
                        'store_name' => 'Store 40',
                        'sku' => $sku, // Assign the generated SKU
                        'sku_desc' => 'Test SKU',
                        'upc' => $upc,
                        'qty_expected' => $qty_ordered,
                        'qty_received' => $qty_received,
                        'unit_cost' => $unit_cost, // Set random unit_cost
                        'unit_retail' => $unit_retail, // Set unit_retail with calculated value
                        'vat_cost' => $vat_cost, // Set vat_cost as 11% of unit_cost
                        'unit_cost_disc' => $unit_discount, // Set unit_discount based on qty_ordered
                        'supplier' => mt_rand(1, 5000), // Random supplier code between 1 and 5000
                        'sup_name' => 'Supplier ' . mt_rand(1, 5000),
                        'comment_desc' => 'Test order',
                    ];

                    // Insert the order data into the array
                    $allRcv[] = $orderData; // Optionally, keep track of all orders in an array
                }

                // Duplicate the selected order numbers
                foreach ($duplicates as $duplicateOrderNo) {
                    // Duplicate the corresponding order data
                    $duplicateData = array_filter($allRcv, function ($order) use ($duplicateOrderNo) {
                        return $order['order_no'] == $duplicateOrderNo;
                    });

                    // Add duplicated order data to the array
                    foreach ($duplicateData as $data) {
                        $allRcv[] = $data;
                    }
                }
            }
        }
        Http::fake([
            $apiEndpointRcv => Http::response(['status' => 'success'], 200)
        ]);
        // Send the data to the API endpoint
        $response = $this->postJson($apiEndpointRcv, $allRcv);
        dd($response);
        // Debug the response
    }
}
