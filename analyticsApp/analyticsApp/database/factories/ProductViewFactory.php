<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductView;
use Faker\Generator as Faker;

$factory->define(ProductView::class, function (Faker $faker) {
    $viewDate = $faker->dateTime('now');

    return [
        'visitor_id' => $faker->uuid,
        'product_id' => $faker->numberBetween(1,10),
        'created_at' => $viewDate,
        'updated_at' => $viewDate
    ];
});
