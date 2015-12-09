"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradas = [];
  $scope.indice = 'Pro-Formas';
  $scope.entradasInsumos = [];
  $scope.cRegistro = '5';
  $scope.proformaVisivility = true;
  $scope.insumosVisivility = false;
  $scope.ordenVisivility = false;
  $scope.orden = {};
  $scope.insumos = [];

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
    uiVisivility(1);
    $scope.obtenerEntradas();
  };

  $scope.registrosInsumos = function(){
    $scope.busqueda = '';
    $scope.indice = 'Insumos';
    uiVisivility(2)
    $scope.obtenerEntradasInsumos();
  };

  $scope.detallesOrden = function(orden){

    $http.get('/getOrden/'+ orden)
      .success( 
        function(response){
          $scope.orden   = response.orden;
          $scope.insumos = response.insumos;
          uiVisivility(3);
          $scope.busqueda = '';
      });
  }

  $scope.detallesEntrada = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/detallesEntrada',
          controller: 'detallesEntradaCtrl',
          windowClass: 'large-Modal',
          resolve: {

             id:function () {
                return index;
             }
         }
    });
  };

  function uiVisivility(menu){
    switch(menu){
      case 1:
        $scope.proformaVisivility = true;
        $scope.insumosVisivility = false;
        $scope.ordenVisivility = false;
      break;

      case 2:
        $scope.proformaVisivility = false;
        $scope.insumosVisivility = true;
        $scope.ordenVisivility = false;
      break;

      case 3:
        $scope.proformaVisivility = false;
        $scope.insumosVisivility = false;
        $scope.ordenVisivility = true;
    }
  }

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
