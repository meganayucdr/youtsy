<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use NamespacedApp\HollandTestDetail;
use Faker\Generator as Faker;

$factory->define(App\HollandTestDetail::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
