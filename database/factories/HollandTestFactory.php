<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use NamespacedApp\HollandTest;
use Faker\Generator as Faker;

$factory->define(App\HollandTest::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
