"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradas = [];
  $scope.indice = 'Pro-Formas';
  $scope.entradasInsumos = [];
  $scope.cRegistro = '5';
  $scope.status = true;

	$scope.obtenerEntradas = function(){

		$http.get('/getEntradas')
			.success( function(response){$scope.entradas = response});
	};

  $scope.obtenerEntradasInsumos = function(){

    $http.get('/getInsumosEntradas')
      .success( function(response){$scope.entradasInsumos = response});
  };

  $scope.registrosProformas = function(){
    $scope.busqueda = '';
    $scope.indice = 'Pro-Formas';
    $scope.status = true;
    $scope.obtenerEntradas();
  };

  $scope.registrosInsumos = function(){
    $scope.busqueda = '';
    $scope.indice = 'Insumos';
    $scope.status = false;
    $scope.obtenerEntradasInsumos();
  };

  $scope.localizarEntrada = function(entrada){
    $scope.indice = 'Pro-Formas';
    $scope.status = true;
    $scope.busqueda = entrada;
  };

  $scope.detallesEntrada = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/detallesEntrada',
          controller: 'detallesEntradaCtrl',
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

angular.module('deposito').controller('detallesEntradaCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.entrada = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  
  };

  $scope.detalles = function(){

    $http.get('/getEntrada/' + id)
      .success(function(response){

        $scope.entrada = response.entrada;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});
