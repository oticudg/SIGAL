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

Route::group(['middleware' => 'auth' ], function(){

	//Inicio del panel de administracion
	Route::get('inicio', function(){
		return view('inicio');
	});

	/*** Modulo Provedores ***/

	Route::group(['middleware' => 'permission:provedores'], function(){

		//Muesta el panel de provedores
		Route::get('proveedores','provedoresController@index');

		Route::group(['middleware' => 'permission:provedoreN'], function(){
			//Muestra la vista de registro de provedor
			Route::get('registraProvedor','provedoresController@viewRegistro');
			//Registra un provedor
			Route::post('registraProvedor' ,'provedoresController@registrar');
		});

		Route::group(['middleware' => 'permission:provedoreM'], function(){
			//Muesta la vista de edicion de provedores
			Route::get('editarProvedor', 'provedoresController@viewEditar');
			//Edita un provedor cuyo id se pase
			Route::post('editProvedor/{id}', 'provedoresController@editProvedor');
		});
		
		Route::group(['middleware' => 'permission:provedoreD'], function(){
			//Muestra la vista de eliminacion de provedores
			Route::get('elimProvedor','provedoresController@viewEliminar');
			//Elimina un provedor cuyo id se pase
			Route::post('elimProvedor/{id}','provedoresController@elimProvedor');
		});
		
		//Obtiene un provedor por su id
		Route::get('getProvedor/{id}', 'provedoresController@getProvedor');

	});

	//Regresa todos los provedores que existan
	Route::get('getProvedores','provedoresController@allProvedores');

	/*** fin de modulo provedores ***/

	
	/*** Modulo de usuarios ***/

	Route::group(['middleware' => 'permission:usuarios'], function(){

		//Muestra el panel de usuarios 
		Route::get('usuarios' , 'usersController@index');

		Route::group(['middleware' => 'permission:usuarioN'], function(){
			//Muestra vista de registro de usuarios
			Route::get('registrarUser','usersController@viewRegistrar');
			//Registra un usuario
			Route::post('registrarUsuario' ,'usersController@registrar');
		});

		Route::group(['middleware' => 'permission:usuarioM'], function(){
			//Muesta la vista de edicion de Usuarios
			Route::get('editarUsuario', 'usersController@viewEditar');
			//Edita un usuario cuyo id se pase
			Route::post('editarUsuario/{id}', 'usersController@editUsuario');
		});

		Route::group(['middleware' => 'permission:usuarioD'], function(){
			//Muestra la vista de eliminacion de usuarios
			Route::get('eliminarUsuario','usersController@viewEliminar');
			//Elimina un usuario cuyo id se pase
			Route::post('eliminarUsuario/{id}','usersController@elimUsuario');
		});

		//Regresa todas los usuarios que existan
		Route::get('getUsuarios','usersController@allUsuarios');
		//Obtiene un usuario por su id
		Route::get('getUsuario/{id}', 'usersController@getUsuario');
	});
	
	/*** fin de modulo usuario ***/


	/*** Modulo de departamentos ***/

	Route::group(['middleware' => 'permission:departamentos'], function(){

		//Muestra el panel de departamentos 
		Route::get('departamentos','departamentosController@index');

		Route::group(['middleware' => 'permission:departamentoN'], function(){
			//Muestra vista de registro de departamento
			Route::get('registrarDepartamento','departamentosController@viewRegistrar');
			//Registra un Departamento
			Route::post('registrarDepartamento' ,'departamentosController@registrar');
		});

		Route::group(['middleware' => 'permission:departamentoM'], function(){
			//Muesta la vista de edicion de departamento
			Route::get('editarDepartamento', 'departamentosController@viewEditar');
			//Edita un departamento cuyo id se pase
			Route::post('editarDepartamento/{id}', 'departamentosController@editDepartamento');
		});

		Route::group(['middleware' => 'permission:departamentoD'], function(){
			//Muestra la vista de eliminacion de departamentos
			Route::get('eliminarDepartamento','departamentosController@viewEliminar');
			//Elimina un departamento cuyo id se pase
			Route::post('eliminarDepartamento/{id}','departamentosController@elimDepartamento');
		});

		//Obtiene un departamento por su id
		Route::get('getDepartamento/{id}', 'departamentosController@getDepartamento');	
		
	});

	//Regresa todas los departamentos que existan
	Route::get('getDepartamentos','departamentosController@allDepartamentos');

	/*** fin de modulo departamentos ***/


	/*** Modulo de Insumos ***/

	Route::group(['middleware' => 'permission:insumos'], function(){

		//Muestra el panel de insumos 
		Route::get('insumos','insumosController@index');

		Route::group(['middleware' => 'permission:insumoN'], function(){
			//Muestra vista de registro de insumo
			Route::get('registrarInsumo','insumosController@viewRegistrar');
			//Registra un insumo
			Route::post('registrarInsumo' ,'insumosController@registrar');
		});

		Route::group(['middleware' => 'permission:insumoM'], function(){	
			//Muesta la vista de edicion de insumo
			Route::get('editarInsumo', 'insumosController@viewEditar');
			//Edita un insumo cuyo id se pase
			Route::post('editarInsumo/{id}', 'insumosController@editInsumo');
		});
		
		Route::group(['middleware' => 'permission:insumoD'], function(){
			//Muestra la vista de eliminacion de insumo
			Route::get('eliminarInsumo','insumosController@viewEliminar');
			//Elimina un insumo cuyo id se pase
			Route::post('eliminarInsumo/{id}','insumosController@elimInsumo');
		});

		//Regresa todas los insumos que existan
		Route::get('getInsumos','insumosController@allInsumos');
		//Obtiene un insumo por su id
		Route::get('getInsumo/{id}', 'insumosController@getInsumo');	
	});

	//Regresa una lista de insumos que coincidan con la descripcion o codigo que se pase
	Route::get('getInsumosConsulta', 'insumosController@getInsumosConsulta');

	/*** fin de modulo Insumos ***/


	/*** Modulo de inventario ***/

	Route::group(['middleware' => 'permission:inventarios'], function(){

		//Muestra el panel de inventario
		Route::get('inventario','inventarioController@index');

		//Muestra la  vista de insumos en niveles bajos y criticos
		Route::get('alertasInsumos',['middleware' => 'alert', 'uses' => 'inventarioController@viewInsumosAlertas']);

		Route::group(['middleware' => 'permission:inventarioH'], function(){
			//Muestra la vista de herramientas
			Route::get('inventarioHerramientas','inventarioController@viewHerramientas');
			//configura el valor min y med de los insumos que se especifiquen
			Route::post('estableceAlarmas','inventarioController@configuraAlarmas');
			//Regresa una lista de insumos que coincidan con la descripcion o codigo que se pase
			Route::get('getInventarioConsulta', 'inventarioController@getInsumosConsulta');
		});

		//Regresa todos las insumos en el inventario
		Route::get('getInventario','inventarioController@allInsumos');

		//Regresa todos los insumos en alerta del inventario
		Route::get('getAlertInsumos','inventarioController@insumosAlert');	
	});
	
	/*** Fin de modulo de inventario ***/


	/*** Modulo de entradas ***/

	Route::group(['prefix' => 'entradas', 'as' => 'entr'], 
		function(){

		Route::group(['middleware' => 'permission:entradas'], function(){

			//Muestra el panel de entradas
			Route::get('panel',['as' => 'Panel', 'uses' => 'entradasController@index']);

			//Muestra la vista detallada de una entrada
			Route::get('detalles', 'entradasController@detalles');

			//Regresa todas las entradas segun el tipo que se espesifique, si no se espesifica un
			//tipo se regresan todas las entradas
			Route::get('getEntradas/{type?}', 'entradasController@allEntradas');

			//Regresa todos los insumos que han entrado segun el tipo que se espesifique, si no se espesifica 
			//tipo se regresan todos los insumos
			Route::get('getInsumos/{type?}', 'entradasController@allInsumos');

			//Regresa todos los datos de una entrada cuyo id y tipo se pase
			Route::get('getEntrada/{type}/{id}', 'entradasController@getEntrada');

			//Regresa todas las entradas de el numero de orden que se expecifique
			Route::get('getOrden/{number}', 'entradasController@getOrden');

		});

		Route::group(['middleware' => 'permission:entradaR'], function(){
			//Muestra la vista de registro de entrada
			Route::get('registrar',['as' => 'Registrar', 'uses' => 'entradasController@viewRegistrar']);
			
			//Registra una entrada segun un tipo que se espesifique
			Route::post('registrar/{type}' ,'entradasController@registrar');
		});

		//Regresa los todos los datos de una entrada cuyo codigo se especifique
		Route::get('getCodigo/{code}', 'entradasController@getEntradaCodigo');
	});

	/*** Fin de modulo de entradas ***/


	/*** Modulo de salidas ***/

	Route::group(['middleware' => 'permission:salidas'], function(){
		//Muestra el panel de salidas
		Route::get('salidas','salidasController@index');
		//Muestra la vista detallada de una salida
		Route::get('detallesSalida','salidasController@detalles');
		//Regresa todos los insumos que han salido
		Route::get('getInsumosSalidas','salidasController@allInsumos');
		//Regresa todas las salidas
		Route::get('getSalidas','salidasController@allSalidas');
		//Regresa los todos los datos de una salida cuyo id se pase
		Route::get('getSalida/{id}', 'salidasController@getSalida');
	});

	Route::group(['middleware' => 'permission:salidaR'], function(){
		//Muestra la vista de registro de salida
		Route::get('registrarSalida', 'salidasController@viewRegistrar');
		//Registra una salida
		Route::post('registrarSalida' ,'salidasController@registrar');
	});

	//Regresa los todos los datos de una salida cuyo codigo se especifique
	Route::get('getSalidaCodigo/{code}', 'salidasController@getSalidaCodigo');

	/*** Fin de modulo de salidas ***/


	/*** Modulo de Modificaciones ***/

	Route::group(['prefix' => 'modificaciones', 'as' => 'modifi', 'middleware' => 'permission:modificaciones'], 
		function(){
		
		/** Modificaciones entradas **/

			//Muestra el panel de modificacione de entradas
			Route::get('entradas',['as' => 'Entrada', 'uses' => 'modificacionesController@indexEntradas']);
			//Muestra la vista detallada de una entrada modificada
			Route::get('detallesEntrada', ['as' => 'DelleEntra', 'uses' => 'modificacionesController@detallesEntrada']);
			//Muestra la vista de registro de modificacion de entrada
			Route::get('registrarEntrada',['as' => 'RvEntra', 'uses' => 'modificacionesController@viewRegEntrada']);
			//Registra una modificacion de entrada
			Route::post('registrarEntrada', ['as' => 'RcEntra', 'uses' => 'modificacionesController@registrarEntrada']);
			//Regresa todas las entradas modificadas
			Route::get('getEntradas',[ 'as' => 'AllEntra', 'uses' => 'modificacionesController@allEntradas']);
			//Regresa todos los datos de una entrada modificada cuyo id se pase
			Route::get('getEntradas/{id}',['as' => 'GetEntra', 'uses' => 'modificacionesController@getEntrada']);

		/** Modificaciones salidas **/
			
			//Muestra el panel de modificacione de salidas
			Route::get('salidas',['as' => 'Salida', 'uses' => 'modificacionesController@indexSalidas']);
			//Muestra la vista detallada de una salida modificada
			Route::get('detallesSalida', ['as' => 'DelleSalid', 'uses' => 'modificacionesController@detallesSalida']);
			//Muestra la vista de registro de modificacion de salida
			Route::get('registrarSalida',['as' => 'RvSalid', 'uses' => 'modificacionesController@viewRegSalida']);
			//Registra una modificacion de salida
			Route::post('registrarSalida', ['as' => 'RcSalid', 'uses' => 'modificacionesController@registrarSalida']);
			//Regresa todas las salidas modificadas
			Route::get('getSalidas',[ 'as' => 'AllSalid', 'uses' => 'modificacionesController@allSalidas']);
			//Regresa todos los datos de una salida modificada cuyo id se pase
			Route::get('getSalida/{id}',['as' => 'GetSalid', 'uses' => 'modificacionesController@getSalida']);
	});

	/*** Fin de modulo de modificaciones ***/

	
	/*** Modulo de Estadisticas ***/

	Route::group(['middleware' => 'permission:estadisticas'], function(){
		//Muesta el panel de estadisticas 
		Route::get('estadisticas', 'estadisticasController@index');

		//Regresa las salidas de todo los servicios del mes actual
		Route::get('getEstadisticas', 'estadisticasController@getServicios');

		//Regresa todas las salidad de un insumo por sevicio en un rango de fecha
		Route::post('estadisticasInsumo', 'estadisticasController@getInsumo');

		//Regresa todas los insumos que han salido de un servicio en un ranfo de fecha
		Route::post('estadisticasServicio', 'estadisticasController@getServicio');
	});

	/*** Fin de modulo de Estadisticas ***/

});