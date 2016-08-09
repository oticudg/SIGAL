"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradas = [];
	$scope.insumos = [];
	$scope.indice  = 'Pro-Formas';
  $scope.cRegistro = '5';
  $scope.status = true;

	var obtenerEntradas = function(){
    $http.get('/entradas/getEntradas/')
			.success( function(response){$scope.entradas = response});
  };

  var obtenerInsumos = function(datos){
    $http.get('/entradas/getInsumos')
      .success( function(response){$scope.insumos = response});
  };

	$scope.registrosProformas = function(){
		$scope.busqueda = '';
		$scope.indice = 'Pro-Formas';
		$scope.insumos = [];
		$scope.status = true;
		obtenerEntradas();
	};

	$scope.registrosInsumos = function(){
		$scope.busqueda = '';
		$scope.indice = 'Insumos';
		$scope.insumos = [];
		$scope.status = false;
		obtenerInsumos();
	};

  $scope.detallesOrden = function(orden){

    $http.get('/entradas/getOrden/'+ orden)
      .success(
        function(response){
          $scope.orden   = response.orden;
          $scope.insumos = response.insumos;
          $scope.busqueda = '';
          $scope.indice = 'Orden';
      });
  }

  $scope.detallesEntrada = function(index){
    var modalInstance = $modal.open({
      animation: true,
          templateUrl: '/entradas/detalles',
          controller: 'detallesEntradaCtrl',
          windowClass: 'large-Modal',
          resolve: {
             id:function () {
                return index;
             }
         }
    });
  };

	obtenerEntradas();
});

angular.module('deposito').controller('detallesEntradaCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.entrada = {};
  $scope.insumos = [];
  $scope.visibility = false;

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');

  };

  $scope.chvisibility = function(){
    $scope.search = {};
    $scope.visibility =  !$scope.visibility ? true:false;
  }

  $scope.detalles = function(){

    $http.get('/entradas/getEntrada/' + id)
      .success(function(response){

        $scope.nota = response.nota;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});
