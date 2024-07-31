<?php

namespace Tests\Unit;

use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Faker\Factory as Faker;


class ExampleTest extends TestCase
{

    /**
     * Test inserting an order through the API.
     */
    public function test_insert_order_through_api(): void
    {
        // Replace with your actual API endpoint
        $apiEndpoint = 'https://application-all.test/api/po/store';

        // Initialize an empty array to store all orders
        $allOrders = [];
        $orderNo = 1;
        $faker = Faker::create();

        // Set the start date to the beginning of the current year
        $startDate = date('Y-01-01');
        $currentDatePlus30 = date('Y-m-d', strtotime('+30 days'));

        // Loop through each day from the start date until 30 days from the current date
        for ($date = $startDate; $date <= $currentDatePlus30; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
            // Generate order data for each date
            for ($entry = 1; $entry <= 20; $entry++) { // Adjust entry count as needed
                $orderNumbers = [];

                // Generate unique order numbers
                for ($i = 1; $i <= 30; $i++) { // Adjust number of unique orders per day
                    $orderNumbers[] = ((int)str_replace('-', '', $date) * 100) + ($entry * 10) + $i;
                }

                // Randomly select 2 to 9 order numbers to duplicate
                $duplicates = $faker->randomElements($orderNumbers, mt_rand(2, min(9, count($orderNumbers))));

                // Generate order data for each set
                foreach ($orderNumbers as $orderNumber) {
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

                    // Generate order data for the item
                    $orderData = [
                        'order_no' => $orderNo++, // Assign the unique order number
                        'ship_to' => mt_rand(40, 400),
                        'supplier' => mt_rand(1, 5000), // Random supplier code between 1 and 5000
                        'terms' => 30,
                        'status_ind' => 10,
                        'written_date' => $date,
                        'not_before_date' => $date,
                        'not_after_date' => $currentDatePlus30,
                        'approval_date' => $date,
                        'approval_id' => '123',
                        'cancelled_date' => null,
                        'canceled_id' => null,
                        'cancelled_amt' => 0,
                        'total_cost' => 100,
                        'total_retail' => 120,
                        'outstand_cost' => 100,
                        'total_discount' => 0,
                        'comment_desc' => 'Test order',
                        'buyer' => 1,
                        'sku' => $sku, // Assign the generated SKU
                        'upc' => $upc,
                        'unit_cost' => $unit_cost, // Set random unit_cost
                        'unit_retail' => $unit_retail, // Set unit_retail with calculated value
                        'vat_cost' => $vat_cost, // Set vat_cost as 11% of unit_cost
                        'luxury_cost' => 0,
                        'qty_ordered' => $qty_ordered, // Set random qty_ordered
                        'qty_received' => 0,
                        'unit_discount' => $unit_discount, // Set unit_discount based on qty_ordered
                        'unit_permanent_discount' => 0,
                        'sku_desc' => 'Test SKU',
                        'purchase_uom' => 'EA',
                        'supp_pack_size' => 1,
                        'permanent_disc_pct' => 0,
                        'tag_code' => 'TST',
                    ];

                    $allOrders[] = $orderData; // Add the order data to the array
                }
            }
        }

        // Optionally, output $allOrders for debugging
        // dd($allOrders);

        // Transform $allOrders into objects if needed (commented out as per your requirement)
        // $allOrders = array_map(function ($order) {
        //     return (object) $order;
        // }, $allOrders);

        // Fake the HTTP response from the API endpoint
        Http::fake([
            $apiEndpoint => Http::response(['status' => 'success'], 200)
        ]);

        // Send a POST request to the API endpoint with JSON-encoded $allOrders
        $response = $this->postJson($apiEndpoint, $allOrders);

        // Assert that the request was successful
        $response->assertStatus(201) // Adjust status as per your API response status
            ->assertJson(['status' => 'success']);

        // Optionally, assert that the data was inserted into the database
        // Adjust 'orders' to match your database table name and $orderData to match your expected data
        // $this->assertDatabaseHas('orders', $orderData);
    }
}
