<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Event;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    return [
        'visitor_id' => $faker->uuid,
        'action_id' => $faker->numberBetween(1,7),
        'page_id' => $faker->numberBetween(1,10),
        'message' => $faker->sentence(5),
        'product_view_id' => null,
        'created_at' => $faker->dateTime('now'),
        'updated_at' => $faker->dateTime
    ];
});
