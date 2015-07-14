<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');


//Muesta el panel de provedores
Route::get('provedores','provedoresController@index');
//Registra un provedor
Route::post('registraProvedor' ,'provedoresController@registrar');
//Regresa todos los provedores que existan
Route::get('getProvedores','provedoresController@allProvedores');
//Obtiene un provedor por su id
Route::get('getProvedor/{id}', 'provedoresController@getProvedor');
//Edita un provedor cuyo id se pase
Route::get('editProvedor/{id}', 'provedoresController@editProvedor');