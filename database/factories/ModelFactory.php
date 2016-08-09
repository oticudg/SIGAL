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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'nombre'         => 'root',
        'apellido'	     => 'deposito',
        'email'          => 'root@sahum.gob.ve',
        'cedula'         => '02617525315',
        'password' 		 => bcrypt('rooot'),
        'remember_token' => str_random(10),
    ];
});
