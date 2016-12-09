"use strict";

angular.module('deposito').
controller('alertController',function($scope,$http){

  $scope.insumoSelect = {};
  $scope.listInsumos = [];
  $scope.insumos = [];
  $scope.loader = true;

  $scope.refreshInsumos = function(insumo){

    var params = {insumo: insumo};
    return $http.get(
      '/inventario/alertas/getInventarioAlert',
      {params: params}
    ).then(function(response){
      $scope.listInsumos =  response.data
    });
  };

  $scope.agregarInsumos = function(){

    if(!$scope.insumoSelect.selected){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }

    if( insumoExist($scope.insumoSelect.selected.codigo) ){
      $scope.alert = {type:"danger" , msg:"Este insumo ya se ha agregado"};
      return;
    }

    $scope.insumos.unshift(
      {
        'id':$scope.insumoSelect.selected.id,
        'codigo':$scope.insumoSelect.selected.codigo,
    	  'descripcion':$scope.insumoSelect.selected.descripcion,
        'min':$scope.insumoSelect.selected.min,
        'med':$scope.insumoSelect.selected.med,
        'promedio':$scope.insumoSelect.selected.promedio
      }
    );

    $scope.insumoSelect = {};
  }

  function insumoExist(codigo){

    var index;

    for(index in $scope.insumos){
      if($scope.insumos[index].codigo  == codigo)
        return true;
    }

    return false;
  };

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({'id': $scope.insumos[index].id, 'min':$scope.insumos[index].min,
  		'med':$scope.insumos[index].med, 'promedio':$scope.insumos[index].promedio});
    }

    return insumos;
  }

  function restablecer(){
    $scope.insumos  = [];
  }

  function validaCantidad(){
    var index;

    for( index in $scope.insumos){

      if( !$scope.insumos[index].min || $scope.insumos[index].min <= 0 ||
          isNaN($scope.insumos[index].min))
        return false;

      if( !$scope.insumos[index].med || $scope.insumos[index].med <= 0 ||
          isNaN($scope.insumos[index].med))
        return false;

      if( !$scope.insumos[index].promedio || $scope.insumos[index].promedio <= 0 ||
          isNaN($scope.insumos[index].promedio))
        return false;

      if( $scope.insumos[index].min >= $scope.insumos[index].med )
        return false;

      if( $scope.insumos[index].min >= $scope.insumos[index].promedio)
        return false;

      if( $scope.insumos[index].med >= $scope.insumos[index].promedio)
        return false;

    }

    return true;
  }

  $scope.eliminarInsumo = function(index){
    $scope.insumos.splice(index, 1);
  };

  $scope.existInsumos = function(){

  	if($scope.insumos.length == 0)
  		return false;

  	return true;
  }

  $scope.closeAlert = function(){
    $scope.alert = {};
    $
  };

  $scope.guardar = function(){

    $scope.loader = false;

  	if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique un valor valido para cada insumo"};
      return;
    }

  	var data = {
  		'insumos':empaquetaData()
  	};

  	$http.post('/inventario/alertas/estableceAlarmas', data)
  	 .success(
  	 	function(response){

      $scope.loader = true;

			$scope.alert = {type:response.status , msg:response.menssage};

	  	 	if( response.status == 'success'){

	  	 		$scope.alert = {type:response.status , msg:response.menssage};
	  	 		restablecer();
	  		}
	  	}
  	);
  }
});
