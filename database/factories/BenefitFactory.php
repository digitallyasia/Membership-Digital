<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Benefit;
use Faker\Generator as Faker;

$factory->define(Benefit::class, function (Faker $faker) {
    return [
        'organization_id' => factory(\App\Organization::class),
        'title' => $faker->sentence(4),
        'details' => $faker->sentence(10),
        'image' => 'http://placeimg.com/400/400/tech',
        'promo_code' => $faker->word,
    ];
});
