"use strict";


angular.module('deposito').
controller('presentacionesController',function($scope,$http,$modal){

	$scope.presentaciones = [];

	$scope.registrarPresentacion = function() {

    	var modalInstance = $modal.open({
     		animation: true,
      		templateUrl: '/registrarPresentacion',
      		size:'lg',
      		controller: 'registraPresentacionCtrl',
      		resolve: {
       			 obtenerPresentaciones: function () {
          			return $scope.obtenerPresentaciones;
        		 }
      		}
	    });
	}

	$scope.obtenerPresentaciones = function(){

		$http.get('/getPresentaciones')
			.success( function(response){$scope.presentaciones = response});
	};


	$scope.editarPresentacion = function(index){

		var modalInstance = $modal.open({

			animation: true,
      		templateUrl: '/editarPresentacion',
      		size:'lg',
      		controller: 'editarPresentacionCtrl',
      		resolve: {
       			 obtenerPresentaciones: function () {
          			return $scope.obtenerPresentaciones;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  /*
  $scope.elimProvedor = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/elimProvedor',
          controller: 'elimProvedorCtrl',
          resolve: {
             obtenerProvedores: function () {
                return $scope.obtenerProvedores;
             },
             id:function () {
                return index;
             }
         }
    });
  };
  */

	$scope.obtenerPresentaciones();

});

angular.module('deposito').controller('registraPresentacionCtrl', function ($scope, $modalInstance, $http, obtenerPresentaciones) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

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

		'nombre'	:  $scope.nombre,
 	};


 	$http.post('/registrarPresentacion',$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerPresentaciones();

 	});
 };

});


angular.module('deposito').controller('editarPresentacionCtrl', function ($scope, $modalInstance, $http, obtenerPresentaciones,id) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

  $http.get('/getPresentacion/' + id)
    .success(function(response){
        $scope.nombre    = response.nombre;
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

		'nombre'	:  $scope.nombre
 	};


 	$http.post('/editarPresentacion/' + id ,$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerPresentaciones();

 	});
 };

});

/*
angular.module('deposito').controller('elimProvedorCtrl', function ($scope, $modalInstance, $http, obtenerProvedores,id) {

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

  $http.post('/elimProvedor/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerProvedores();
  });

 };

});

*/