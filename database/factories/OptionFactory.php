<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use NamespacedApp\Option;
use Faker\Generator as Faker;

$factory->define(App\Option::class, function (Faker $faker) {
    return [
        'option' => $faker->name,
        'weight' => $faker->randomDigit
    ];
});
