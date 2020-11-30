<?php

use Illuminate\Database\Seeder;
use App\{Service, ServiceList, ServicePayment};

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Service::class, 45)->create()->each(function ($service) {
            $service->lists()->saveMany(factory(ServiceList::class, mt_rand(1, 5))->make());
            $service->payments()->saveMany(factory(ServicePayment::class, mt_rand(1, 3))->make());
        });
    }
}
