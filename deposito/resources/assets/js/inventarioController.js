"use strict";

angular.module('deposito').
controller('inventarioController',function($scope,$http,$modal){

	$scope.insumos = [];
	$scope.cRegistro = '5';
	$scope.status = false; 
	$scope.all = false;

	$scope.obtenerInsumos = function(){

		$http.get('/inventario/getInventario')
			.success( function(response){
				$scope.insumos = response;
				$scope.calculaEstatus($scope.insumos);
			});
	};

	$scope.calculaEstatus = function(insumos){

		for( var insumo in insumos){

			if( insumos[insumo].existencia <= insumos[insumo].min){
				insumos[insumo].color = "danger";
			}
			else if(insumos[insumo].existencia <= insumos[insumo].med){
				insumos[insumo].color = "warning";
			}
			else{
				insumos[insumo].color = "";
			}	
		}
	}
	
	$scope.parcialInventario = function(){
		$scope.status = true;
	}

	$scope.closeSelect = function(){
		$scope.status = false;
		$scope.unselectInsumos();
		$scope.calculaEstatus($scope.insumos);
		$scope.all = false;
	}

	$scope.unselectInsumos = function(){
		for( var insumo in $scope.insumos){
			$scope.insumos[insumo].select = false;
			$scope.insumos[insumo].color  = ""; 
		}
	}

	$scope.select = function(){

		if($scope.all){
			$scope.selectAll();
		}
		else{
			$scope.unselectInsumos();
		}
	}

	$scope.selectAll = function(){

		if(!$scope.busqueda){
			for( var insumo in $scope.insumos){
				$scope.insumos[insumo].color = "success";
				$scope.insumos[insumo].select = true;
			}
		}
		else{
			for( var insumo in $scope.insumos){

				var descripcion = $scope.insumos[insumo].descripcion.toLowerCase();
				var busqueda = $scope.busqueda.toLowerCase();

				if( descripcion.indexOf(busqueda) == -1 )
					continue;

				$scope.insumos[insumo].color = "success";
				$scope.insumos[insumo].select = true;
			}
		}
	}

	$scope.selectInsumo = function(index){

		if($scope.status == false)
			return;

		if($scope.insumos[index].select){

			$scope.insumos[index].color = "";
			$scope.insumos[index].select = false;
		}
		else{
			$scope.insumos[index].color = "success";
			$scope.insumos[index].select = true;
		}
	};

	$scope.gerenarParcial = function(){
		
		var data = {
			'insumos':empaquetaData($scope.insumos)
		};

		if($scope.thereIsSelect()){

			$http.post('/reportes/getInventario',data, {responseType:'arraybuffer'})
	  			.success(function (response) {

	       			var file = new Blob([response], {type: 'application/pdf'});
	       			var fileURL = URL.createObjectURL(file);
	       			window.open(fileURL);
			});
  		}
	}

	$scope.thereIsSelect = function(){

		for( var insumo in $scope.insumos){
			if($scope.insumos[insumo].select)
				return true;
		}

		return false;
	}

	function empaquetaData(insumos){

		var insumosSelect = [];

		for( var insumo in insumos){

			if( insumos[insumo].select )
				insumosSelect.push(insumos[insumo].id);
		}

		return insumosSelect;
	}

	$scope.obtenerInsumos();

});