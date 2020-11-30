<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\{Doctor, DoctorPayment};
use Faker\Generator as Faker;

$factory->define(Doctor::class, function (Faker $faker) {
    return [
    	'code' => $faker->unique()->numberBetween($min = 1000, $max = 1015),
        'name' => $faker->name,
        'mobile' => $faker->phoneNumber,
        'email' => $faker->freeEmail,
        'address' => $faker->address,
        'specialty' => $faker->sentence,
        'qualification' => $faker->sentence,
        'image' => 'no_image.png',
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});

$factory->define(DoctorPayment::class, function (Faker $faker) {
    return [
    	'invoice' => $faker->unique()->numberBetween($min = 1016, $max = 1100),
        'doctor_id' => $faker->numberBetween($min = 1, $max = 10),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'amount' => $faker->numberBetween($min = 500, $max = 10000),
        'description' => $faker->text($maxNbChars = 200),
        'created_by' => 1
    ];
});
