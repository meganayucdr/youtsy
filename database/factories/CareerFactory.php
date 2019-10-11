<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use NamespacedApp\Career;
use Faker\Generator as Faker;

$factory->define(App\Career::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
