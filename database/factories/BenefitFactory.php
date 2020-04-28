<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Benefit;
use Faker\Generator as Faker;

$factory->define(Benefit::class, function (Faker $faker) {
    return [
        'organization_id' => factory(\App\Organization::class),
        'title' => $faker->sentence(4),
        'details' => $faker->word,
        'image' => $faker->word,
        'promocode' => $faker->word,
    ];
});
