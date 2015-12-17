"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradas = [];
  $scope.indice  = 'Pro-Formas';
  $scope.insumos = [];
  $scope.insumosDon = [];
  $scope.cRegistro = '5';
  $scope.orden = {};
  $scope.insumos = [];
  $scope.uiStatus = {
    'proformas' : true,
    'insumos'   : false,
    'ordenes'   : false
  };

	var obtenerEntradas = function(datos){

    switch(datos){
  		
      case 'orden':
        $http.get('/entradas/getEntradas/'+ datos)
    			.success( function(response){$scope.entradas = response});
      break;

      case 'donacion':
        $http.get('/entradas/getEntradas/'+ datos)
          .success( function(response){$scope.entradas = response});
      break;

      case 'devolucion':
        $http.get('/entradas/getEntradas/'+ datos)
          .success( function(response){$scope.entradas = response});
      break;

      case 'toda':
        $http.get('/entradas/getEntradas')
          .success( function(response){$scope.entradas = response});
      break;
    }

  };

  var obtenerInsumos = function(datos){
      
      switch(datos){

        case 'orden':
          $http.get('/entradas/getInsumos/'+ datos)
            .success( function(response){$scope.insumos = response});
        break;

        case 'donacion':
          $http.get('/entradas/getInsumos/'+ datos)
            .success( function(response){$scope.insumos = response});
        break;

        case 'devolucion':
          $http.get('/entradas/getInsumos/'+ datos)
            .success( function(response){$scope.insumos = response});
        break;

        case 'todo':
          $http.get('/entradas/getInsumos')
            .success( function(response){$scope.insumos = response});
        break;
      }
  };

  $scope.registrosEntradas = function(datos){
    
    $scope.busqueda = '';
    $scope.indice = 'Pro-Formas';
    visivility(1);

    switch(datos){

      case 'ordenes':
        obtenerEntradas('orden');
      break;

      case 'donaciones':
        obtenerEntradas('donacion')
      break;

      case 'devoluciones':
        obtenerEntradas('devolucion');
      break;

      case 'todas':
        obtenerEntradas('toda');
      break;
    };

  };

  $scope.registrosInsumos = function(datos){
    $scope.busqueda = '';
    $scope.indice = 'Insumos';
    visivility(2);
    
    switch(datos){

      case 'ordenes':
        obtenerInsumos('orden');
      break;

      case 'donaciones':
        obtenerInsumos('donacion');
      break;

      case 'devoluciones':
        obtenerInsumos('devolucion');
      break;

      case 'todos':
        obtenerInsumos('todo');
      break;
    }
  };

  $scope.detallesOrden = function(orden){

    $http.get('/entradas/getOrden/'+ orden)
      .success( 
        function(response){
          $scope.orden   = response.orden;
          $scope.insumos = response.insumos;
          visivility(3);
          $scope.busqueda = '';
          $scope.indice = 'Orden';
      });
  }

  $scope.detallesEntrada = function(index, type){
    var modalInstance = $modal.open({
      animation: true,
          templateUrl: '/entradas/detalles',
          controller: 'detallesEntradaCtrl',
          windowClass: 'large-Modal',
          resolve: {

             id:function () {
                return index;
             },
             type:function(){
                return type;
             }

         }
    });
  };

  function visivility(menu){
    switch(menu){
      case 1:
        $scope.uiStatus.proformas = true;
        $scope.uiStatus.insumos = false;
        $scope.uiStatus.ordenes = false;
      break;

      case 2:
        $scope.uiStatus.proformas = false;
        $scope.uiStatus.insumos = true;
        $scope.uiStatus.ordenes = false;
      break;

      case 3:
        $scope.uiStatus.proformas = false;
        $scope.uiStatus.insumos = false;
        $scope.uiStatus.ordenes = true;
    }
  }

	obtenerEntradas('toda');

});

angular.module('deposito').controller('detallesEntradaCtrl', function ($scope, $modalInstance, $http, type, id) {

  $scope.entrada = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  
  };

  $scope.detalles = function(){

    $http.get('/entradas/getEntrada/'+ type + '/' + id)
      .success(function(response){

        $scope.entrada = response.entrada;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});
