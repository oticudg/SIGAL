"use strict";

angular.module('deposito').
controller('insumosAlertController',function($scope,$http,$modal){

	$scope.insumos = [];
	$scope.insumosv = [];

	$scope.cRegistro = '10';

	$scope.obtenerInsumos = function(){

		$http.get('/inventario/herramientas/getNivelesAlert')
			.success( function(response){$scope.insumos = response});
	};

	$scope.obtenerInsumosv = function(){

		$http.get('/inventario/herramientas/getVencimientosAlert')
			.success( function(response){$scope.insumosv = response});
	}

	$scope.calculaEstatus = function( min , med , exit){

		if( exit <=  min)
			return "danger";

		if(exit <= med)
			return "warning";
	}

	$scope.obtenerInsumos();
});
