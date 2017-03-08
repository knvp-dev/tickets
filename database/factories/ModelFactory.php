<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'color' => $faker->hexcolor,
        'avatar' => $faker->imageUrl($width = 200, $height = 200),
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Customer::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail
    ];
});

$factory->define(App\Ticket::class, function (Faker\Generator $faker) {
    return [
        'customer_id' => App\Customer::all()->random()->id,
        'title' => $faker->sentence($nbWords = 6),
        'description' => $faker->paragraph,
        'status_id' => App\Status::all()->random()->id,
        'category_id' => App\Category::all()->random()->id,
        'priority_id' => App\Priority::all()->random()->id,
        'owner_id' => App\User::all()->random()->id,
        'completed' => 0,
        'archived' => 0,
        'date_closed' => null
    ];
});

$factory->define(App\Todo::class, function (Faker\Generator $faker) {
    return [
        'ticket_id' => App\Ticket::all()->random()->id,
        'body' => $faker->sentence($nbWords = 3),
        'completed' => 0
    ];
});

$factory->define(App\Status::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'color' => $faker->hexcolor
    ];
});

$factory->define(App\Priority::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(App\Message::class, function(Faker\Generator $faker){
    return [
        'user_id' => App\User::all()->random()->id,
        'ticket_id' => App\Ticket::all()->random()->id,
        'body' => $faker->sentence($nbWords = 10),
    ];
});
