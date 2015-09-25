"use strict";

angular.module('deposito').
controller('registroEntradaController',function($scope,$http){

  $scope.codigo;
  $scope.insumoSelect = {};  
  $scope.provedores = [];
  $scope.provedor;
  $scope.insumos = [];
  $scope.listInsumos = [];
  $scope.alert = {};

  $http.get('/getProvedores')
    .success( function(response){ $scope.provedores = response;});

  $http.get('/getInsumos')
    .success( function(response){ $scope.listInsumos = response;});

  $scope.agregarInsumos = function(){

    if(!$scope.insumoSelect.selected){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }
    
    if( insumoExist($scope.insumoSelect.selected.codigo) ){
      $scope.alert = {type:"danger" , msg:"Este insumo ya se ha agregado en esta entrada"};
      return; 
    }

    $scope.insumos.push(
      {
        'id':$scope.insumoSelect.selected.id, 
        'codigo':$scope.insumoSelect.selected.codigo, 
        'descripcion':$scope.insumoSelect.selected.descripcion
      }
    );

    $scope.insumoSelect = {};
  }

  $scope.registroEntrada = function(){

    var $data = {

      'codigo'  : $scope.codigo,
      'provedor': $scope.provedor,
      'insumos' : empaquetaData()
    };

    if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique un valor valido para cada insumo"};
      return;
    }

    $http.post('/registrarEntrada', $data)
      .success( 
        function(response){
          $scope.alert = {type:response.status , msg: response.menssage};
          
          if( response.status == 'success'){
            restablecer();
          }
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
      if( !$scope.insumos[index].cantidad || $scope.insumos[index].cantidad  < 0 || 
          !Number.isInteger($scope.insumos[index].cantidad))
        return false;
    }

    return true;
  }

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({'id': $scope.insumos[index].id, 'cantidad':$scope.insumos[index].cantidad});
    }

    return insumos;
  }

  function restablecer(){
    $scope.insumos  = [];
    $scope.codigo   = '';
    $scope.provedor = '';
  }

});