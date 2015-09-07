"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradas = [];

	$scope.obtenerInsumos = function(){

		$http.get('/getEntradas')
			.success( function(response){$scope.entradas = response});
	};

  $scope.elimInsumo = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/eliminarInsumo',
          controller: 'eliminarInsumoCtrl',
          resolve: {
             obtenerInsumos: function () {
                return $scope.obtenerInsumos;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerInsumos();

});

angular.module('deposito').controller('eliminarInsumoCtrl', function ($scope, $modalInstance, $http, obtenerInsumos,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

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
     
      obtenerInsumos();
  });
 };

});
