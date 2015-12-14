"use strict";

angular.module('deposito').
controller('registroEntradaController',function($scope, $http ,$modal){

  $scope.insumoSelect = {};  
  $scope.provedores = [];
  $scope.provedorOrd;
  $scope.provedorDon;
  $scope.insumosOrd = [];
  $scope.insumosDon = [];
  $scope.listInsumos = [];
  $scope.alert = {};

  $scope.refreshInsumos = function(insumo) {
      var params = {insumo: insumo};
      return $http.get(
        '/getInsumosConsulta',
        {params: params}
      ).then(function(response){
        $scope.listInsumos =  response.data
      });
  };

  $http.get('/getProvedores')
    .success( function(response){ $scope.provedores = response;});

  $scope.agregarInsumos = function( insumos ){

    if(!$scope.insumoSelect.selected){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }
      
    if( insumoExist($scope.insumoSelect.selected.codigo, insumos) ){
      $scope.alert = {type:"danger" , msg:"Este insumo ya se ha agregado en esta entrada"};
      return; 
    }

    insumos.push(
      {
        'id':$scope.insumoSelect.selected.id, 
        'codigo':$scope.insumoSelect.selected.codigo, 
        'descripcion':$scope.insumoSelect.selected.descripcion
      }
    );
    
    $scope.insumoSelect = {};

  }

  $scope.registroOrden = function(){

    var $data = {

      'orden'  : $scope.orden,
      'provedor': $scope.provedorOrd,
      'insumos' : empaquetaData($scope.insumosOrd)
    };

    if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique un valor valido para cada insumo"};
      return;
    }

    $http.post('/registrarOrd', $data)
      .success( 
        function(response){
          
          if( response.status == 'success'){
            
            $modal.open({
                animation: true,
                templateUrl: 'successRegister.html',
                controller: 'successRegisterCtrl',
                resolve: {
                  response: function () {
                    return response;
                  }
                }
            });

            restablecerOrd();
            return;
          }

          $scope.alert = {type:response.status , msg: response.menssage};
        }
      );
  }

  $scope.registroDona = function(){

    var $data = {
      'provedor': $scope.provedorDon,
      'insumos' : empaquetaData( $scope.insumosDon)
    };

    if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique un valor valido para cada insumo"};
      return;
    }

    $http.post('/registrarDon', $data)
      .success( 
        function(response){
          
          if( response.status == 'success'){
            
            $modal.open({
                animation: true,
                templateUrl: 'successRegister.html',
                controller: 'successRegisterCtrl',
                resolve: {
                  response: function () {
                    return response;
                  }
                }
            });

            restablecerDon();
            return;
          }

          $scope.alert = {type:response.status , msg: response.menssage};
        }
    );
  }

  $scope.eliminarInsumo = function(index , insumos){
    insumos.splice(index, 1);
  };

  $scope.closeAlert = function(){
    $scope.alert = {};
  };

  $scope.thereInsumos = function( insumos ){
    return insumos.length > 0 ? true:false;
  };

  function insumoExist(codigo, insumos){

    var index;

    for(index in insumos){
      if(insumos[index].codigo  == codigo)
        return true;
    }

    return false;
  };

  function validaCantidad(){
    var index;

    for( index in $scope.insumosOrd){
      if( !$scope.insumosOrd[index].cantidad || $scope.insumosOrd[index].cantidad  < 0 || 
          !Number.isInteger($scope.insumosOrd[index].cantidad))
        return false;
    }

    return true;
  }

  function empaquetaData(insumosDate){

    var index;
    var insumos = [];

    for( index in insumosDate){
      insumos.push({'id': insumosDate[index].id, 'cantidad':insumosDate[index].cantidad});
    }

    return insumos;
  }

  function restablecerOrd(){
    $scope.insumosOrd  = [];
    $scope.orden   = '';
    $scope.provedorOrd = '';
    $scope.alert = {};
  }

  function restablecerDon(){
    $scope.insumosDon  = [];
    $scope.provedorDon = '';
    $scope.alert = {};
  }

});

angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});