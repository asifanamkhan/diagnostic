<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{TestCategory, Test};
use Faker\Generator as Faker;

$factory->define(TestCategory::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween($min = 101, $max = 200),
        'name' => $faker->word,
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});

$factory->define(Test::class, function (Faker $faker) {
    return [
    	'name' => $faker->word,
    	'code' => $faker->unique()->numberBetween($min = 200, $max = 1000),
        'test_category_id' => $faker->numberBetween($min = 1, $max = 5), 
        'cost' => $faker->numberBetween($min = 500, $max = 10000),
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});
