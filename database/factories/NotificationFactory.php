<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Notification;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    return [
        'organization_id' => factory(\App\Organization::class),
        'title' => $faker->sentence(4),
        'body' => $faker->sentence(10),
    ];
});
