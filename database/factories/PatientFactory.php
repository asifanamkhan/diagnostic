<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Patient;
use Faker\Generator as Faker;

$factory->define(Patient::class, function (Faker $faker) {
    return [
        'code' => $faker->unique()->numberBetween($min = 1200, $max = 1260),
        'name' => $faker->name,
        'mobile' => $faker->phoneNumber,
        'email' => $faker->freeEmail,
        'age' => $faker->randomNumber('2'),
        'gender' => $faker->randomElement(['male', 'female', 'others']),
        'address' => $faker->address,
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});
