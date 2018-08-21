<?php

use \Ramsey\Uuid\Uuid as Uuid;
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

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'channel_id' => function () {
            return factory('App\Channel')->create()->id;
        },
        'title' => $faker->sentence,
        'body' => $faker->paragraph
    ];
});

$factory->define(App\Channel::class, function (Faker $faker) {
    $name = $faker->word;
    return [
        'name' => $name,
        'slug' => $name,
    ];
});

$factory->define(App\Reply::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'thread_id' => function () {
            return factory('App\Thread')->create()->id;
        },
        'body' => $faker->paragraph
    ];
});

$factory->define(App\Vote::class, function (Faker $faker) {
    $votables = [
        // App\Thread::class,
        App\Reply::class,
    ];

    $votedType = $faker->randomElement($votables);

    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'voted_id' => function () {
            return factory($votedType)->create()->id;
        },
        'voted_type' => $votedType,
        'score' => $faker->randomElement([1, -1]),
    ];
});

$factory->define(App\Activity::class, function (Faker $faker) {
    $subjectTypes = [
        App\Thread::class,
        App\Reply::class,
        App\Vote::class,
    ];

    $subjectType = $faker->randomElement($subjectTypes);

    return [
        'user_id' => function () {
            return factory('App\User')->create()->id;
        },
        'type' => 'created_' . explode('\\', strtolower($subjectType))[1],
        'subject_id' => function () {
            return factory($subjectType)->create()->id;
        },
        'subject_type' => $subjectType,
    ];
});

$factory->define(App\Subscription::class, function (Faker $faker) {
    return [
        //
    ];
});

$factory->define(\Illuminate\Notifications\DatabaseNotification::class, function (Faker $faker) {
    return [
        'id' => Uuid::uuid4(),
        'type' => 'App\Notifications\ThreadUpdated',
        'notifiable_id' => function() {
            return auth()->id() ?: factory('App\User')->create()->id;
        },

        'notifiable_type' => 'App\User',
        'data' => [$faker->name => $faker->name],
    ];
});
