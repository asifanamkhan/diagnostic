<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Service, ServiceList, ServicePayment};
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
	$total_amount = $faker->numberBetween($min = 500, $max = 10000);
	$discount = $faker->numberBetween($min = 0, $max = 50);
	$paid_amount = $faker->numberBetween($min = 0, $max = ($total_amount - (($total_amount/100)*$discount)));

    return [
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    	'invoice' => $faker->unique()->numberBetween($min = 1600, $max = 1700),
        'patient_id' => $faker->numberBetween($min = 1, $max = 50),
        'doctor_id' => $faker->numberBetween($min = 1, $max = 10),
        'delivery_date' => $faker->dateTime($max = 'now', $timezone = null)->format('Y-m-d H:i:s'), 
        'total_amount' => $total_amount,
        'discount' => $discount,
        'paid_amount' => $paid_amount,
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});

$factory->define(ServiceList::class, function (Faker $faker) {
    return [
        'test_id' => $faker->numberBetween($min = 1, $max = 20), 
        'cost' => $rate = $faker->numberBetween($min = 100, $max = 5000),
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});

$factory->define(ServicePayment::class, function (Faker $faker) {
    return [
    	'invoice' => $faker->unique()->numberBetween($min = 1700, $max = 10000),
        'payment_type' => $faker->numberBetween($min = 1, $max = 3),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'amount' => $faker->numberBetween($min = 500, $max = 10000),
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});
