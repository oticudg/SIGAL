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


/*** Modulo Provedores ***/

//Muesta el panel de provedores
Route::get('provedores','provedoresController@index');

//Muestra la vista de registro de provedor
Route::get('registraProvedor','provedoresController@viewRegistro');
//Registra un provedor
Route::post('registraProvedor' ,'provedoresController@registrar');

//Muesta la vista de edicion de provedores
Route::get('editarProvedor', 'provedoresController@viewEditar');
//Edita un provedor cuyo id se pase
Route::post('editProvedor/{id}', 'provedoresController@editProvedor');

//Muestra la vista de eliminacion de provedores
Route::get('elimProvedor','provedoresController@viewEliminar');
//Elimina un provedor cuyo id se pase
Route::post('elimProvedor/{id}','provedoresController@elimProvedor');

//Regresa todos los provedores que existan
Route::get('getProvedores','provedoresController@allProvedores');
//Obtiene un provedor por su id
Route::get('getProvedor/{id}', 'provedoresController@getProvedor');

/*** fin de modulo provedores ***/


/*** Modulo presentaciones ***/

//Muestra el panel de presentaciones 
Route::get('presentaciones' , 'presentacionesController@index');

//Muestra vista de registro de presentaciones
Route::get('registrarPresentacion','presentacionesController@viewRegistro');
//Registra una presentacion
Route::post('registrarPresentacion' ,'presentacionesController@registrar');

//Muesta la vista de edicion de Presentaciones
Route::get('editarPresentacion', 'presentacionesController@viewEditar');
//Edita uná presentacion cuyo id se pase
Route::post('editarPresentacion/{id}', 'presentacionesController@editPresentacion');

//Muestra la vista de eliminacion de provedores
Route::get('eliminarPresentacion','presentacionesController@viewEliminar');
//Elimina un provedor cuyo id se pase
Route::post('eliminarPresentacion/{id}','presentacionesController@elimPresentacion');

//Regresa todas las presentaciones que existan
Route::get('getPresentaciones','presentacionesController@allPresentaciones');
//Obtiene una presentacion por su id
Route::get('getPresentacion/{id}', 'presentacionesController@getPresentacion');

/*** fin de modulo presentaciones ***/


/*** Modulo secciones ***/

//Muestra el panel de secciones 
Route::get('secciones' , 'seccionesController@index');

//Muestra vista de registro de secciones
Route::get('registrarSeccion','seccionesController@viewRegistro');
//Registra una presentacion
Route::post('registrarSeccion' ,'seccionesController@registrar');

//Muesta la vista de edicion de Presentaciones
Route::get('editarSeccion', 'seccionesController@viewEditar');
//Edita uná presentacion cuyo id se pase
Route::post('editarSeccion/{id}', 'seccionesController@editSeccion');

//Muestra la vista de eliminacion de provedores
Route::get('eliminarSeccion','seccionesController@viewEliminar');
//Elimina un provedor cuyo id se pase
Route::post('eliminarSeccion/{id}','seccionesController@elimSeccion');

//Regresa todas las secciones que existan
Route::get('getSecciones','seccionesController@allSecciones');
//Obtiene una seccion por su id
Route::get('getSeccion/{id}', 'seccionesController@getSeccion');

/*** fin de modulo secciones ***/


/*** Modulo de usuarios ***/

//Muestra el panel de usuarios 
Route::get('usuarios' , 'usersController@index');

//Muestra vista de registro de usuarios
Route::get('registrarUser','usersController@viewRegistrar');
//Registra un usuario
Route::post('registrarUsuario' ,'usersController@registrar');

//Muesta la vista de edicion de Usuarios
Route::get('editarUsuario', 'usersController@viewEditar');
//Edita un usuario cuyo id se pase
Route::post('editarUsuario/{id}', 'usersController@editUsuario');

//Muestra la vista de eliminacion de usuarios
Route::get('eliminarUsuario','usersController@viewEliminar');
//Elimina un usuario cuyo id se pase
Route::post('eliminarUsuario/{id}','usersController@elimUsuario');

//Regresa todas los usuarios que existan
Route::get('getUsuarios','usersController@allUsuarios');
//Obtiene un usuario por su id
Route::get('getUsuario/{id}', 'usersController@getUsuario');

/*** fin de modulo usuario ***/


/*** Modulo de departamentos ***/

//Muestra el panel de departamentos 
Route::get('departamentos','departamentosController@index');

//Muestra vista de registro de departamento
Route::get('registrarDepartamento','departamentosController@viewRegistrar');
//Registra un Departamento
Route::post('registrarDepartamento' ,'departamentosController@registrar');

//Muestra la vista de eliminacion de departamentos
Route::get('eliminarDepartamento','departamentosController@viewEliminar');
//Elimina un departamento cuyo id se pase
Route::post('eliminarDepartamento/{id}','departamentosController@elimDepartamento');

//Regresa todas los departamentos que existan
Route::get('getDepartamentos','departamentosController@allDepartamentos');

/*** fin de modulo departamentos ***/



/*** Modulo de Insumos ***/

//Muestra el panel de insumos 
Route::get('insumos','insumosController@index');

//Muestra vista de registro de insumo
Route::get('registrarInsumo','insumosController@viewRegistrar');
//Registra un insumo
Route::post('registrarInsumo' ,'insumosController@registrar');

//Muesta la vista de edicion de insumo
Route::get('editarInsumo', 'insumosController@viewEditar');
//Edita un insumo cuyo id se pase
Route::post('editarInsumo/{id}', 'insumosController@editInsumo');

//Muestra la vista de eliminacion de insumo
Route::get('eliminarInsumo','insumosController@viewEliminar');
//Elimina un insumo cuyo id se pase
Route::post('eliminarInsumo/{id}','insumosController@elimInsumo');

//Regresa todas los insumos que existan
Route::get('getInsumos','insumosController@allInsumos');
//Obtiene un insumo por su id
Route::get('getInsumo/{id}', 'insumosController@getInsumo');

/*** fin de modulo Insumos ***/
