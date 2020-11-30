<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Supplier;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween($min = 10, $max = 100),
        'name' => $faker->name,
        'mobile' => $faker->phoneNumber,
        'email' => $faker->freeEmail,
        'address' => $faker->address,
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});
