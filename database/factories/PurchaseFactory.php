<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Purchase, PurchaseList, PurchasePayment};
use Faker\Generator as Faker;

$factory->define(Purchase::class, function (Faker $faker) {
	$total_amount = $faker->numberBetween($min = 500, $max = 10000);
	$discount = $faker->numberBetween($min = 0, $max = $total_amount);
	$shipping_cost = $faker->numberBetween($min = 100, $max = 1500);
	$final_amount = ($total_amount + $shipping_cost) - $discount;

    return [
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    	'invoice' => $faker->unique()->numberBetween($min = 1401, $max = 1500),
        'supplier_id' => $faker->numberBetween($min = 1, $max = 10), 
        'total_amount' => $total_amount,
        'discount' => $discount,
        'shipping_cost' => $shipping_cost,
        'final_amount' => $final_amount,
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});

$factory->define(PurchaseList::class, function (Faker $faker) {
	$quantity = $faker->numberBetween($min = 1, $max = 500);
	$rate = $faker->numberBetween($min = 10, $max = 5000);
	$amount = $quantity * $rate;

    return [
        'product_id' => $faker->numberBetween($min = 1, $max = 30), 
        'quantity' => $quantity,
        'rate' => $rate,
        'amount' => $amount,
        'created_by' => 1
    ];
});

$factory->define(PurchasePayment::class, function (Faker $faker) {
    return [
    	'invoice' => $faker->unique()->numberBetween($min = 1500, $max = 1600),
        'supplier_id' => $faker->numberBetween($min = 1, $max = 10),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'amount' => $faker->numberBetween($min = 500, $max = 10000),
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});
