<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text(255),
        'begin_date' => null,
        'end_date' => null,
        'status' => $faker->randomElement(['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed'])
    ];
});
