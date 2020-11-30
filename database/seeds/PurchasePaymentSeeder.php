<?php

use Illuminate\Database\Seeder;
use App\PurchasePayment;

class PurchasePaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PurchasePayment::class, 30)->create();
    }
}
