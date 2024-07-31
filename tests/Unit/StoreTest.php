<?php

namespace Tests\Unit;

use App\Models\Store;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{

    /**
     * Test inserting values from 40 to 950 into the store.
     *
     * @return void
     */
    public function testInsertFrom40To950()
    {
        // Insert values from 40 to 950
        for ($value = 40; $value <= 950; $value++) {
            Store::create([
                'store' => $value,
                'store_name' => "Store {$value}",
                'store_add1' => "Address 1 for Store {$value}",
                'store_city' => 'Anytown',
                'region' => 'XYZ',
            ]);
        }

        // Assert the count of records in the database
        $this->assertEquals(911, Store::count()); // 950 - 40 + 1 = 911 items
    }


    // Add more test cases as needed for edge cases and specific scenarios
}
