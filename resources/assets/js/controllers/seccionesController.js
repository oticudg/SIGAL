"use strict";


angular.module('deposito').
controller('seccionesController',function($scope,$http,$modal){

	$scope.secciones = [];

	$scope.registrarSeccion = function() {

    	var modalInstance = $modal.open({
     		animation: true,
      		templateUrl: '/registrarSeccion',
      		size:'lg',
      		controller: 'registraSeccionCtrl',
      		resolve: {
       			 obtenerSecciones: function () {
          			return $scope.obtenerSecciones;
        		 }
      		}
	    });
	}

	$scope.obtenerSecciones = function(){

		$http.get('/getSecciones')
			.success( function(response){$scope.secciones = response});
	};


	$scope.editarSeccion = function(index){

		var modalInstance = $modal.open({

			animation: true,
      		templateUrl: '/editarSeccion',
      		size:'lg',
      		controller: 'editarSeccionCtrl',
      		resolve: {
       			 obtenerSecciones: function () {
          			return $scope.obtenerSecciones;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  $scope.eliminarSeccion = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/eliminarSeccion',
          controller: 'eliminarSeccionesCtrl',
          resolve: {
             obtenerSecciones: function () {
                return $scope.obtenerSecciones;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerSecciones();

});

angular.module('deposito').controller('registraSeccionCtrl', function ($scope, $modalInstance, $http, obtenerSecciones) {

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


 	$http.post('/registrarSeccion',$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerSecciones();

 	});
 };

});


angular.module('deposito').controller('editarSeccionCtrl', function ($scope, $modalInstance, $http, obtenerSecciones,id) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

  $http.get('/getSeccion/' + id)
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


 	$http.post('/editarSeccion/' + id ,$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerSecciones();

 	});
 };

});

angular.module('deposito').controller('eliminarSeccionesCtrl', function ($scope, $modalInstance, $http, obtenerSecciones,id) {

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

  $http.post('/eliminarSeccion/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerSecciones();
  });
 };

});
