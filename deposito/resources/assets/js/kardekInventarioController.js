"use strict";

angular.module('deposito').
controller('kardekController',function($scope,$http,$modal){

	$scope.movimentos = [];
	$scope.cRegistro = '5';
  
	$scope.obtenerKardek = function(){

    var data = {
      'insumo':insumo
    }

		$http.post('/inventario/kardek',data)
			.success( function(response){
				$scope.movimentos = response.kardek;
			});
	};

	$scope.obtenerKardek();

});
