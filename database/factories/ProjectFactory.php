<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Project;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text(255),
        'begin_date' => Carbon::parse('2020-01-01')->format('Y-m-d'),
        'end_date' => Carbon::parse('2020-02-01')->format('Y-m-d'),
        'status' => $faker->randomElement(['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed']),
    ];
});
