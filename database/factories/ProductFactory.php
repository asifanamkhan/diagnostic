<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{ProductCategory, Product, StockAdjustment};
use Faker\Generator as Faker;

$factory->define(ProductCategory::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween($min = 1260, $max = 1300),
        'name' => $faker->word,
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});

$factory->define(Product::class, function (Faker $faker) {
	$purchased = $faker->numberBetween($min = 500, $max = 10000);
	$used = $faker->numberBetween($min = 0, $max = $purchased);
	$stock = $purchased - $used;
    return [
    	'name' => $faker->word,
    	'code' => $faker->unique()->numberBetween($min = 1300, $max = 1400),
    	'unit' => $faker->randomElement(['1', '2']),
        'product_category_id' => $faker->numberBetween($min = 1, $max = 10),
        'purchased' => $purchased,
        'used' => $used,
        'stock' => $stock,
        'image' => 'no_image.png',
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});

$factory->define(StockAdjustment::class, function (Faker $faker) {
    $prev_quantity = $faker->numberBetween($min = 500, $max = 10000);
    $adjusted_quantity = $faker->numberBetween($min = 10, $max = $prev_quantity);
    $after_quantity = $prev_quantity - $adjusted_quantity;

    return [
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'product_id' => $faker->numberBetween($min = 1, $max = 30), 
        'adjustment_class' => $faker->numberBetween($min = 1, $max = 2),
        'adjustment_type' => 1,
        'prev_quantity' => $prev_quantity,
        'adjusted_quantity' => $adjusted_quantity,
        'after_quantity' => $after_quantity,
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});