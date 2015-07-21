"use strict";


angular.module('deposito').
controller('departamentosController',function($scope,$http,$modal){

	$scope.presentaciones = [];

/*
	$scope.obtenerPresentaciones = function(){

		$http.get('/getPresentaciones')
			.success( function(response){$scope.presentaciones = response});
	};


	$scope.editarPresentacion = function(index){

		var modalInstance = $modal.open({

			animation: true,
      		templateUrl: '/editarPresentacion',
      		size:'lg',
      		controller: 'editarPresentacionCtrl',
      		resolve: {
       			 obtenerPresentaciones: function () {
          			return $scope.obtenerPresentaciones;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  $scope.eliminarPresentacion = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/eliminarPresentacion',
          controller: 'eliminarPresentacionCtrl',
          resolve: {
             obtenerPresentaciones: function () {
                return $scope.obtenerPresentaciones;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerPresentaciones();
*/
});