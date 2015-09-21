"use strict";

angular.module('deposito').
controller('registroSalidaController',function($scope,$http){

  $scope.codigoInsumo = '';
  $scope.departamentos = [];
  $scope.servicio = '';
  $scope.insumos = [];
  $scope.alert = {};
  
  getDepartamentos();
  
  $scope.agregarInsumos = function(){

    if( $scope.codigoInsumo  == ''){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }

    if( insumoExist($scope.codigoInsumo) ){
      $scope.alert = {type:"danger" , msg:"Este insumo ya se ha agregado en esta entrada"};
      return; 
    }

    $http.get('/getInsumoCode/' + $scope.codigoInsumo)
      .success(

        function(response){
          if( response.status == 'danger'){
            $scope.alert = {"type":response.status , "msg":response.menssage};
          }
          else{
            $scope.insumos.push(response);
            $scope.codigoInsumo = '';
          }
        }
      );
  }

  $scope.registroEntrada = function(){

    var $data = {
      'departamento': $scope.servicio,
      'insumos'     : empaquetaData()
    };

    if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique valores validos para cada insumo"};
      return;
    }

    $http.post('/registrarSalida', $data)
      .success( 
        function(response){
          /*$scope.alert = {type:response.status , msg: response.menssage};
          
          if( response.status == 'success'){
            restablecer();
          }*/
          console.log(response);
        }
      );
  }

  $scope.eliminarInsumo = function(index){
    $scope.insumos.splice(index, 1);
  };

  $scope.closeAlert = function(){
    $scope.alert = {};
  };

  function insumoExist(codigo){

    var index;

    for(index in $scope.insumos){
      if($scope.insumos[index].codigo  == codigo)
        return true;
    }

    return false;
  };

  function validaCantidad(){
    var index;

    for( index in $scope.insumos){
      if( !$scope.insumos[index].despachado || $scope.insumos[index].despachado < 0 || 
          !Number.isInteger($scope.insumos[index].despachado) || 
          $scope.insumos[index].solicitado < $scope.insumos[index].despachado)
        return false;
    }

    return true;
  }

  function getDepartamentos(){

    $http.get('/getDepartamentos')
      .success( function(response){ $scope.departamentos = response;});
  }

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({'id': $scope.insumos[index].id, 'solicitado':$scope.insumos[index].solicitado, 
        'despachado':$scope.insumos[index].despachado});
    }

    return insumos;
  }

  function restablecer(){
    $scope.insumos  = [];
    $scope.servicio = '';
  }

});