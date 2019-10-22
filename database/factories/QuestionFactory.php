<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use NamespacedApp\Question;
use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'question' => $faker->name,
    ];
});
