<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Page;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'url' => '/'.$faker->word,
        'visit_count' => $faker->numberBetween(1,200)
    ];
});
