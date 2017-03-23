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
        'avatar' => $faker->imageUrl($width = 200, $height = 200),
        'password' => $password ?: $password = bcrypt('secret'),
        'role_id' => function(){
            return factory('App\Role')->create()->id;
        },
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Ticket::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence($nbWords = 4),
        'description' => $faker->paragraph,
        'status_id' => function() {
            return factory('App\Status')->create()->id;
        },
        'category_id' => function() {
            return factory('App\Category')->create()->id;
        },
        'priority_id' => function() {
            return factory('App\Priority')->create()->id;
        },
        'owner_id' => function() {
            return factory('App\User')->create()->id;
        },
        'team_id' => function(){
            return factory('App\Team')->create()->id;
        },
        'completed' => 0,
        'archived' => 0,
        'deadline' => null,
        'date_closed' => null
    ];
});

$factory->define(App\Todo::class, function (Faker\Generator $faker) {
    return [
        'ticket_id' => function() {
            return factory('App\Ticket')->create()->id;
        },
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
        'user_id' => function() {
            return factory('App\User')->create()->id;
        },
        'ticket_id' => function() {
            return factory('App\Ticket')->create()->id;
        },
        'body' => $faker->sentence($nbWords = 10),
    ];
});

$factory->define(App\Team::class, function(Faker\Generator $faker){
    return [
        'title' => $faker->word,
        'owner_id' => function() {
            return factory('App\User')->create()->id;
        }
    ];
});

$factory->define(App\Role::class, function(Faker\Generator $faker){
    return [
        'name' => $faker->word
    ];
});
