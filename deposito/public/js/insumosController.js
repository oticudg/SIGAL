"use strict";

angular.module('deposito').
controller('insumosController',function($scope,$http,$modal){

	$scope.insumos = [];

	$scope.registrarInsumo = function() {

    	var modalInstance = $modal.open({
     		animation: true,
      		templateUrl: '/registrarInsumo',
      		size:'lg',
      		controller: 'registraInsumoCtrl',
      		resolve: {
       			 obtenerInsumos: function () {
          			return $scope.obtenerPresentaciones;
        		 }
      		}
	    });
	}

	$scope.obtenerInsumos = function(){

		$http.get('/getPresentaciones')
			.success( function(response){$scope.insumos = response});
	};

	$scope.obtenerInsumos();

});

angular.module('deposito').controller('registraInsumoCtrl', function ($scope, $modalInstance, $http, Upload, obtenerInsumos) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';
  $scope.secciones= [];
  $scope.presentaciones = [];


  $http.get('/getSecciones')
  	.success(function(response){$scope.secciones = response});

  $http.get('/getPresentaciones')
  	.success(function(response){$scope.presentaciones = response});


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

		'codigo'			:  $scope.codigo,
		'principio_activo'	:  $scope.principio_activo,
		'marca'				:  $scope.marca,
		'presentacion'		:  $scope.presentacion,
		'seccion'			:  $scope.seccion,
		'medida'			:  $scope.medida,
		'cantidadM'			:  $scope.cantidadM,
		'cantidadX'			:  $scope.cantidadX,
		'ubicacion'			:  $scope.ubicacion,
		'deposito'			:  $scope.deposito,
		'descipcion'		:  $scope.descipcion,
 	};


 	Upload.upload({
        url: '/registrarInsumo',
        fields: $data,
        file: $scope.file

    }).success(function(response){

		$scope.alerts = [];
		$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      console.log(response);

      obtenerInsumos();

 	});

 };

});

