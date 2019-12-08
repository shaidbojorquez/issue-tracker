<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Issue;
use Faker\Generator as Faker;

$factory->define(Issue::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text(255),
        'type' => $faker->randomElement(['bug', 'enhancement', 'proposal', 'task']),
        'priority' => $faker->randomElement(['trivial', 'minor', 'major', 'critical', 'blocker']),
        'status' => $faker->randomElement(['open', 'on hold', 'resolved', 'duplicate', 'invalid', 'wontfix', 'closed'])
    ];
});
