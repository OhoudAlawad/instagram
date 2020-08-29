<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Follower::class, function (Faker $faker) {
    return [
        'to_user_id' => App\User::where('name', 'omar')->first()->id,
        'from_user_id' => App\User::all()->random()->id,
        'accepted'=> 0,
        'created_at' => $faker->dateTimeBetween('-1 years', 'now'),
    ];
});
