<?php

use Illuminate\Database\Seeder;
use App\StockAdjustment;

class StockAdjustmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(StockAdjustment::class, 10)->create();
    }
}
