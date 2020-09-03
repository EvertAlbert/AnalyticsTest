<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Visitor;
use Faker\Generator as Faker;

$factory->define(Visitor::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'language_id' => $faker->numberBetween(1,3), //note for myself: php artisan tinker --> factory(App\Visitor::class)->create(['language' => 1])
        'age' => $faker->numberBetween(1, 100),
        'arrival_time' => $faker->dateTime('now'),
        'departure_time' => $faker->dateTime,
        'rating' => $faker->numberBetween(0, 2)
    ];
});
