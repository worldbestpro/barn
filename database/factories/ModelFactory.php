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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name'           => $faker->name,
        'email'          => $faker->email,
        'password'       => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Models\RabbitTest::class, function (Faker\Generator $faker) {
    return [
        'name'  => $faker->name,
        'sex'   => $faker->name,
        'born'  => \Carbon\Carbon::now()->toDateTimeString(),
        'image' => 'rabbit.png',
    ];
});
$factory->define(App\Models\Role::class, function (Faker\Generator $faker) {
    return [
        'name'         => $faker->word,
        'display_name' => $faker->word,
        'description'  => $faker->sentence(6),
    ];
});
$factory->define(App\Models\Permission::class, function (Faker\Generator $faker) {
    return [
        'name'         => $faker->word,
        'display_name' => $faker->word,
        'description'  => $faker->sentence(6),
    ];
});
$factory->define(App\Models\RabbitBreeder::class, function (Faker\Generator $faker) {
    return [
        'name'    => $faker->firstName,
        'cage'    => $faker->randomDigit,
        'tattoo'  => $faker->randomDigit,
        'sex'     => $faker->randomElement(['buck', 'doe']),
        'color'   => $faker->colorName,
        'weight'  => $faker->randomDigit,
        'aquired' => '01/01/2016',
        'notes'   => $faker->sentence(6),
        'image'   => $faker->randomElement(['rabbit1.jpg', 'rabbit2.jpg', 'rabbit3.jpg']),
        'user_id' => 1,
    ];
});
$factory->define(App\Models\Litter::class, function (Faker\Generator $faker) {
    return [
        'given_id'       => $faker->randomDigit,
        'kits_amount'    => 5,
        'bred'           => '01/01/2016',
        'born'           => '01/03/2016',
        'total_weight'   => null,
        'average_weight' => null,
        'survival_rate'  => 100,
        'notes'          => $faker->sentence(5),
        'user_id'        => 1,
    ];
});
$factory->define(App\Models\RabbitKit::class, function (Faker\Generator $faker) {
    return [
        'given_id'       => $faker->randomDigit,
        'color'          => $faker->colorName,
        'sex'            => $faker->randomElement(['doe', 'buck']),
        'current_weight' => $faker->randomFloat(null, 0, 10),
        'user_id'        => 1,
    ];
});