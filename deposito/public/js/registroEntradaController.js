"use strict";

angular.module('deposito').
controller('registroEntradaController',function($scope,$http){

  $scope.codigo;
  $scope.codigoInsumo;
  $scope.provedor;
  $scope.insumos = [];

  $scope.agregarInsumos = function(){

    $http.get('/getInsumoCode/' + $scope.codigoInsumo)
      .success(
          function(response){
              $scope.insumos.push(response);
          }
      );
  }

});