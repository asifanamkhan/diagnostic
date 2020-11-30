<?php

use Illuminate\Database\Seeder;
use App\DoctorPayment;

class DoctorPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DoctorPayment::class, 50)->create();
    }
}
