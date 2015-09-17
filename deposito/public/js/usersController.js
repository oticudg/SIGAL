"use strict";


angular.module('deposito').
controller('usersController',function($scope,$http,$modal){

	$scope.usuarios = [];
  $scope.cRegistro = '5';

	$scope.registrarUser = function() {

    	$modal.open({
     		animation: true,
      		templateUrl: '/registrarUser',
      		size:'lg',
      		controller: 'registraUsuarioCtrl',
      		resolve: {
       			 obtenerUsuarios: function () {
          			return $scope.obtenerUsuarios;
        		 }
      		}
	    });
	}

	$scope.obtenerUsuarios = function(){

		$http.get('/getUsuarios')
			.success( function(response){$scope.usuarios = response;});
	};

	$scope.editarUsuario = function(index){

	  $modal.open({

			animation: true,
      		templateUrl: '/editarUsuario',
      		size:'lg',
      		controller: 'editarUsuarioCtrl',
      		resolve: {
       			 obtenerUsuarios: function () {
          			return $scope.obtenerUsuarios;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  $scope.elimUsuario = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/eliminarUsuario',
          controller: 'elimUsuarioCtrl',
          resolve: {
             obtenerUsuarios: function () {
                return $scope.obtenerUsuarios;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerUsuarios();

});

angular.module('deposito').controller('registraUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';
  $scope.apellido = '';
  $scope.cedula = '';
  $scope.email = ''; 
  $scope.password = '';
  $scope.password_confirmation = '';
  $scope.rol = '';
  $scope.rango = '';


  $scope.registrar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {

  'nombre':$scope.nombre,
  'apellido':$scope.apellido,
  'cedula':$scope.cedula,
  'email':$scope.email,
  'password':$scope.password,
  'password_confirmation' : $scope.password_confirmation,
  'rol':$scope.rol,
  'rango':$scope.rango

  };


 	$http.post('/registrarUsuario',$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerUsuarios();

 	});
 };

});

angular.module('deposito').controller('editarUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios,id) {

  $scope.btnVisivilidad = true;

  $scope.nombre    = '';
  $scope.apellido  = '';
  $scope.rol       = '';
  $scope.rango     = '';
  $scope.email     = '';
  $scope.cedula    = '';

  $http.get('/getUsuario/' + id)
    .success(function(response){

        $scope.nombre    = response.nombre;
        $scope.apellido  = response.apellido;
        $scope.rol       = response.rol;
        $scope.rango     = response.rango;
        $scope.email     = response.email;
        $scope.cedula    = response.cedula;

  });


  $scope.modificar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };

 $scope.save = function(){

 	var $data = {

		'nombre'	  :  $scope.nombre,
		'apellido'	:  $scope.apellido,
		'rol'	      :  $scope.rol,
		'rango'		  :  $scope.rango,
    'cedula'    :  $scope.cedula
 	};


 	$http.post('/editarUsuario/' + id ,$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerUsuarios();

 	});
 };

});

angular.module('deposito').controller('elimUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/eliminarUsuario/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerUsuarios();
  });

 };

});