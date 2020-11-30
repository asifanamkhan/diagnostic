<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{ExpenseCategory, Expense};
use Faker\Generator as Faker;

$factory->define(ExpenseCategory::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween($min = 1100, $max = 1120),
        'name' => $faker->word,
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});

$factory->define(Expense::class, function (Faker $faker) {
    return [
    	'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
    	'invoice' => $faker->unique()->numberBetween($min = 1120, $max = 1200),
        'expense_category_id' => $faker->numberBetween($min = 1, $max = 10), 
        'amount' => $faker->numberBetween($min = 500, $max = 10000),
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});
