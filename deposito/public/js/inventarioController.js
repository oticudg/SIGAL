"use strict";

angular.module('deposito').
controller('inventarioController',function($scope,$http,$modal){

	$scope.insumos = [];

	$scope.obtenerInsumos = function(){

		$http.get('/getInventario')
			.success( function(response){$scope.insumos = response});
	};

	$scope.obtenerInsumos();

});
