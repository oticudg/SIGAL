"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradas = [];
  $scope.entradasInsumos = [];
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
    $scope.status = true;
    $scope.obtenerEntradas();
  };

  $scope.registrosInsumos = function(){
    $scope.status = false;
    $scope.obtenerEntradasInsumos();
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

  $scope.btnVisivilidad = true;

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/eliminarInsumo/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

  });
 };

});
