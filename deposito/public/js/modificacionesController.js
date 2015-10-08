"use strict";

angular.module('deposito').
controller('modificacionesController',function($scope,$http,$modal){

	$scope.entradas = [];
  $scope.cRegistro = '5';

	$scope.obtenerEntradas = function(){

		$http.get('/getEntradasModificadas')
			.success( function(response){$scope.entradas = response});
	};

	$scope.obtenerEntradas();

});