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


// Authentication routes...
Route::get('/', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');


//Inicio del panel de administracion
Route::get('inicio', function(){
	return view('inicio');
});

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


/*** Modulo de unidad de medidas ***/

//Muestra el panel de unidades de medidas 
Route::get('medidas','unidadMedidasController@index');

//Muestra vista de registro de unidad de medida
Route::get('registrarMedida','unidadMedidasController@viewRegistrar');
//Registra una unidad de medida
Route::post('registrarMedida' ,'unidadMedidasController@registrar');

//Muesta la vista de edicion de unidad de medida
Route::get('editarMedida', 'unidadMedidasController@viewEditar');
//Edita una unidad de medida cuyo id se pase
Route::post('editarMedida/{id}', 'unidadMedidasController@editUnidad');

//Muestra la vista de eliminacion de unidad de medidas
Route::get('eliminarMedida','unidadMedidasController@viewEliminar');
//Elimina una unidad de medida cuyo id se pase
Route::post('eliminarMedida/{id}','unidadMedidasController@elimUnidad');

//Regresa todas las unidades de medidas  que existan
Route::get('getMedidas','unidadMedidasController@allUnidades');
//Obtiene una unidad de medida por su id
Route::get('getMedida/{id}', 'unidadMedidasController@getUnidad');

/*** Fin de modulo unidad de medidas ***/


/*** Modulo de inventario ***/

//Muestra el panel de inventario
Route::get('inventario','inventarioController@index');

//Muestra la vista de herramientas
Route::get('inventarioHerramientas','inventarioController@viewHerramientas');

//Regresa todos las insumos en el inventario
Route::get('getInventario','inventarioController@allInsumos');

//configura el valor min y med de los insumos que se especifiquen
Route::post('estableceAlarmas','inventarioController@configuraAlarmas');

/*** Fin de modulo de inventario ***/


/*** Modulo de entradas ***/

//Muestra el panel de entradas
Route::get('entradas','entradasController@index');

//Muestra la vista de registro de entrada
Route::get('registrarEntrada', 'entradasController@viewRegistrar');
//Registra una entrada
Route::post('registrarEntrada' ,'entradasController@registrar');

//Muestra la vista detallada de una entrada
Route::get('detallesEntrada','entradasController@detalles');

//Regresa todas las entradas
Route::get('getEntradas','entradasController@allEntradas');

//Regresa todos los insumos que han entrado
Route::get('getInsumosEntradas','entradasController@allInsumos');

//Regresa los todos los datos de una entrada cuyo id se pase
Route::get('getEntrada/{id}', 'entradasController@getEntrada');

//Regresa todas las entradas de el numero de orden que se expecifique
Route::get('getOrden/{number}', 'entradasController@getOrden');

/*** Fin de modulo de entradas ***/


/*** Modulo de salidas ***/

//Muestra el panel de salidas
Route::get('salidas','salidasController@index');

//Muestra la vista detallada de una salida
Route::get('detallesSalida','salidasController@detalles');

//Muestra la vista de registro de salida
Route::get('registrarSalida', 'salidasController@viewRegistrar');
//Registra una salida
Route::post('registrarSalida' ,'salidasController@registrar');

//Regresa todas las salidas
Route::get('getSalidas','salidasController@allSalidas');

//Regresa todos los insumos que han salido
Route::get('getInsumosSalidas','salidasController@allInsumos');

//Regresa los todos los datos de una salida cuyo id se pase
Route::get('getSalida/{id}', 'salidasController@getSalida');

/*** Fin de modulo de salidas ***/


/*** Modulo de Modificaciones ***/

//Muestra el panel de modificaciones
Route::get('modificaciones','modificacionesController@index');

//Regresa todas las entradas modificadas
Route::get('getEntradasModificadas','modificacionesController@allEntradas');

/*** Fin de modulo de modificaciones ***/

