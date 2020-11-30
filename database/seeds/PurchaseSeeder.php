<?php

use Illuminate\Database\Seeder;
use App\{Purchase, PurchaseList};

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Purchase::class, 20)->create()->each(function ($purchase) {
            $purchase->lists()->saveMany(factory(PurchaseList::class, mt_rand(1, 5))->make());
        });
    }
}
