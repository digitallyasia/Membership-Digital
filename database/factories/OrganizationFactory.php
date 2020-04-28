<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Organization;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'logo' => $faker->word,
        'description' => $faker->text,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->word,
        'city' => $faker->city,
        'state' => $faker->word,
        'postal_code' => $faker->postcode,
        'user_id' => factory(\App\User::class),
    ];
});
