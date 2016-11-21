"use strict";

angular.module('deposito').
controller('insumosAlertController',function($scope,$http,$modal){

	$scope.insumos = [];
	$scope.cRegistro = '10';

	$scope.obtenerInsumos = function(){

		$http.get('/inventario/herramientas/getAlertInsumos')
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
