<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Announcement;
use Faker\Generator as Faker;

$factory->define(Announcement::class, function (Faker $faker) {
    return [
        'organization_id' => factory(\App\Organization::class),
        'title' => $faker->sentence(4),
        'details' => $faker->sentence(10),
        'image' => 'http://placeimg.com/400/400/tech',
        'url' => $faker->url
    ];
});
