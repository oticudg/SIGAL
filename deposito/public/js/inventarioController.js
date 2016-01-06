"use strict";

angular.module('deposito').
controller('inventarioController',function($scope,$http,$modal){

	$scope.insumos = [];
	$scope.cRegistro = '5';

	$scope.obtenerInsumos = function(){

		$http.get('/inventario/getInventario')
			.success( function(response){$scope.insumos = response});
	};

	$scope.calculaEstatus = function( min , med , exit){

		if( exit <=  min)
			return "danger";

		if(exit <= med)
			return "warning";
	}

	$scope.obtenerInsumos();

});

