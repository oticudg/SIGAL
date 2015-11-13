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

$factory->define(App\Privilegio::class, function (Faker\Generator $faker) {
    return [
        'usuario'        => 1,
        'usuarios'       => 1,
        'usuarioN'       => 1,
        'usuarioM'       => 1,
        'usuarioD'       => 1,
        'provedores'     => 1,
        'provedoreN'     => 1,
        'provedoreM'     => 1,
        'provedoreD'     => 1,
        'departamentos'  => 1,
        'departamentoN'  => 1,
        'departamentoD'  => 1,
        'insumos' 		 => 1,
        'insumoN' 		 => 1,
        'insumoM' 		 => 1,
        'insumoD' 		 => 1,
        'inventarios' 	 => 1,
        'inventarioH' 	 => 1,
        'entradas' 		 => 1,
        'entradaR'       => 1,
        'salidas' 		 => 1,
        'salidaR' 		 => 1,
        'modificaciones' => 1,
        'estadisticas'   => 1
    ];
});
