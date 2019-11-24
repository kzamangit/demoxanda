<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Spaceship;
use App\Armament;

$factory->define(Spaceship::class, function (Faker $faker) {
    return [
        'name' => 'Spaceship name '.$faker->randomNumber(),
        'class' => 'Class '.$faker->randomNumber(),
        'crew' => $faker->numberBetween(100,100000),
        'image' => 'http://xanda.com/images/'.$faker->numberBetween(1,100),
        'value' => $faker->randomFloat(2, 10, 200000),
        'status' =>  $faker->randomElement([
            'operational','Active','Faluty','Inactive','Damaged' ])
    ];
});

$factory->define(Armament::class, function (Faker $faker) {
    return [
        'title' => 'Armanent Title '.$faker->randomNumber(),
        'qty' => $faker->numberBetween(100,20000)
    ];
});