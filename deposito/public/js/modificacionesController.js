"use strict";

angular.module('deposito').
controller('modificacionesController',function($scope,$http,$modal){

	$scope.entradas = [];
  	$scope.cRegistro = '5';

	$scope.obtenerEntradas = function(){

		$http.get('/getEntradasModificadas')
			.success( function(response){$scope.entradas = response});
	};

	$scope.detallesModificacion = function(index){

	    var modalInstance = $modal.open({

	      animation: true,
	          templateUrl: '/detallesEntradaModificada',
	          controller: 'detallesModificacionCtrl',
	          size:'lg',
	          resolve: {

	             id:function () {
	                return index;
	             }
	         }
	    });
  	};

  	$scope.obtenerEntradas();

});

angular.module('deposito').controller('detallesModificacionCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.entrada = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  
  };

  $scope.detalles = function(){

    $http.get('/getEntradasModificada/' + id)
      .success(function(response){

      	$scope.modificacion = response.modificacion;
        $scope.entrada = response.entrada;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});
