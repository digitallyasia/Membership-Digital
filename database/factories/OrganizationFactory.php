<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Organization;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'logo' => 'http://placeimg.com/200/200/tech',
        'description' => $faker->text,
        'email' => $faker->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => \Str::random(10),
        'phone' => $faker->phoneNumber,
        'address' => $faker->streetAddress,
        'city' => $faker->city,
        'state' => $faker->state,
        'postal_code' => $faker->postcode,
        'auto_join' => 1,
        'website' => 'https://www.google.com',
        'facebook' => 'https://www.facebook.com',
        'instagram' => 'https://www.instagram.com',
        'youtube' => 'https://www.youtube.com',
        'whatsapp' => 'https://web.whatsapp.com',
    ];
});
