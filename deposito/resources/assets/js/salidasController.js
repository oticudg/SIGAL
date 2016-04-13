"use strict";

angular.module('deposito').
controller('salidasController',function($scope,$http,$modal){

	$scope.salidas = [];
  $scope.indice = 'Pro-Formas';
  $scope.salidasInsumos = [];
  $scope.cRegistro = '5';
  $scope.status = true;

	$scope.obtenerSalidas = function(){

		$http.get('/getSalidas')
			.success( function(response){$scope.salidas = response});
	};

  $scope.obtenerSalidasInsumos = function(){

    $http.get('/getInsumosSalidas')
      .success( function(response){$scope.salidasInsumos = response});
  };

  $scope.registrosProformas = function(){
    $scope.busqueda = '';
    $scope.indice = 'Pro-Formas';
		$scope.salidasInsumos = [];
    $scope.status = true;
    $scope.obtenerSalidas();
  };

  $scope.registrosInsumos = function(){
    $scope.busqueda = '';
    $scope.indice = 'Insumos';
		$scope.insumos = [];
		$scope.status = false;
    $scope.obtenerSalidasInsumos();
  };

  $scope.detallesSalida = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/detallesSalida',
          controller: 'detallesSalidaCtrl',
          windowClass: 'large-Modal',
          resolve: {

             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerSalidas();

});

angular.module('deposito').controller('detallesSalidaCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.salida = {};
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

    $http.get('/getSalida/' + id)
      .success(function(response){

        $scope.salida = response.salida;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);


});
