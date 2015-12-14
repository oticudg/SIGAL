"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradasOrd = [];
  $scope.entradasDon = [];
  $scope.indiceOrd = 'Pro-Formas';
  $scope.indiceDon = 'Pro-Formas';
  $scope.insumosOrd = [];
  $scope.insumosDon = [];
  $scope.cRegistro = '5';
  $scope.orden = {};
  $scope.insumos = [];
  $scope.uiOrd = {
    'proformas' : true,
    'insumos'  : false,
    'ordenes'    : false
  };
  $scope.uiDon = {
    'proformas' : true,
    'insumos'  : false,
  };

	$scope.obtenerOrds = function(){

		$http.get('/getEntradasOrd')
			.success( function(response){$scope.entradasOrd = response});
	};

  $scope.obtenerDons = function(){

    $http.get('/getEntradasDon')
      .success( function(response){$scope.entradasDon = response});
  };

  $scope.obtenerInsumosOrd = function(){

    $http.get('/getInsumosOrd')
      .success( function(response){$scope.insumosOrd = response});
  };

  $scope.obtenerInsumosDon = function(){

    $http.get('/getInsumosDon')
      .success( function(response){$scope.insumosDon = response});
  };

  $scope.registrosOrds = function(){
    $scope.busqueda = '';
    $scope.indiceOrd = 'Pro-Formas';
    ordVisivility(1);
    $scope.obtenerOrds();
  };

  $scope.registrosDons = function(){
    $scope.busqueda = '';
    $scope.indiceDon = 'Pro-Formas';
    donVisivility(1);
    $scope.obtenerDons();
  };

  $scope.registrosInsumosOrd = function(){
    $scope.busqueda = '';
    $scope.indiceOrd = 'Insumos';
    ordVisivility(2);
    $scope.obtenerInsumosOrd();
  };

  $scope.registrosInsumosDon = function(){
    $scope.busqueda = '';
    $scope.indiceDon = 'Insumos';
    donVisivility(2);
    $scope.obtenerInsumosDon();
  };

  $scope.detallesOrden = function(orden){

    $http.get('/getOrden/'+ orden)
      .success( 
        function(response){
          $scope.orden   = response.orden;
          $scope.insumos = response.insumos;
          ordVisivility(3);
          $scope.busqueda = '';
          $scope.indiceOrd = 'Orden';
      });
  }

  $scope.detallesEntradaOrd = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/detallesEntrada',
          controller: 'detallesEntradaCtrl',
          windowClass: 'large-Modal',
          resolve: {

             id:function () {
                return index;
             },
             type:function(){
                return 'EO';
             }

         }
    });
  };

  $scope.detallesEntradaDon = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/detallesEntrada',
          controller: 'detallesEntradaCtrl',
          windowClass: 'large-Modal',
          resolve: {

             id:function () {
                return index;
             },
             type:function(){
                return 'ED';
             }

         }
    });
  };


  function ordVisivility(menu){
    switch(menu){
      case 1:
        $scope.uiOrd.proformas = true;
        $scope.uiOrd.insumos = false;
        $scope.uiOrd.ordenes = false;
      break;

      case 2:
        $scope.uiOrd.proformas = false;
        $scope.uiOrd.insumos = true;
        $scope.uiOrd.ordenes = false;
      break;

      case 3:
        $scope.uiOrd.proformas = false;
        $scope.uiOrd.insumos = false;
        $scope.uiOrd.ordenes = true;
    }
  }

  function donVisivility(menu){
    switch(menu){
      case 1:
        $scope.uiDon.proformas = true;
        $scope.uiDon.insumos = false;
      break;

      case 2:
        $scope.uiDon.proformas = false;
        $scope.uiDon.insumos = true;
    }
  }

	$scope.obtenerOrds();

});

angular.module('deposito').controller('detallesEntradaCtrl', function ($scope, $modalInstance, $http, type, id) {

  $scope.entrada = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  
  };

  $scope.detalles = function(){

    $http.get('/getEntrada/'+ type + '/' + id)
      .success(function(response){

        $scope.entrada = response.entrada;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});
