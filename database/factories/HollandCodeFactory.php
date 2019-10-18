<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use NamespacedApp\HollandCode;
use Faker\Generator as Faker;

$factory->define(App\HollandCode::class, function (Faker $faker) {
    return [
        'code' => $faker->randomLetter,
        'name' => $faker->word,
        'explanation' => $faker->sentence($nbWords = 6, $variableNbWords = true)
    ];
});
