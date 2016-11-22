"use strict";

angular.module('deposito').
controller('registroEntradaController',function($scope, $http ,$modal){

  $scope.insumoSelect = {};
  $scope.terceroSelect = {};
  $scope.documentoSelect = {};
  $scope.documentos = [];
  $scope.terceros = [];
  $scope.insumos = [];
  $scope.listInsumos = [];
  $scope.alert = {};


  $scope.openI = function($event, dI) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.insumos[dI].dI = true;
  };

  $scope.refreshInsumos = function(insumo){

      var params = {insumo: insumo};
      return $http.get(
        '/administracion/insumos/getInsumosConsulta',
        {params: params}
      ).then(function(response){
        $scope.listInsumos =  response.data
      });
  };

  $http.get('/administracion/documentos/all/entradas')
      .success( function(response){ $scope.documentos = response;});

  $scope.agregarInsumos = function(){

    if(!$scope.insumoSelect.selected){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }

    if( insumoExist($scope.insumoSelect.selected.codigo, $scope.insumos) ){
      $scope.alert = {type:"danger" , msg:"Este insumo ya se ha agregado en esta entrada"};
      return;
    }

    $scope.insumos.unshift(
      {
        'id':$scope.insumoSelect.selected.id,
        'codigo':$scope.insumoSelect.selected.codigo,
        'descripcion':$scope.insumoSelect.selected.descripcion,
        'dI':null
      }
    );

    $scope.insumoSelect = {};
  }

  $scope.registrar = function(){

    if( !validaCantidad($scope.insumos) ){
      $scope.alert = {type:"danger" , msg:"Especifique un valor valido para cada insumo"};
      return;
    }

    $scope.modalInstance = $modal.open({
      animation: true,
      templateUrl: 'confirmeRegister.html',
      'scope':$scope
    });


    $scope.cancel = function () {
      $scope.modalInstance.dismiss('cancel');
    };

    $scope.cofirme = function(){
        save();
        $scope.modalInstance.dismiss('cancel');
        $scope.loader = true;
    }
  }

  var save = function(){

    var data = {
      'documento': parseDocumento(),
      'tercero' : parseTercero(),
      'insumos'  : empaquetaData()
    };

    $http.post('/transferencias/entradas/registrar', data)
      .success(
        function(response){
          $scope.loader = false;

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

            $scope.restablecer();
            return;
          }

          $scope.alert = {type:response.status , msg: response.menssage};
        }
    );
  }

  $scope.eliminarInsumo = function(index){
    $scope.insumos.splice(index, 1);
  };

  $scope.closeAlert = function(){
    $scope.alert = {};
  };

  $scope.thereInsumos = function(){
    return $scope.insumos.length > 0 ? true:false;
  };

  function insumoExist(codigo, insumos){

    var index;

    for(index in insumos){
      if(insumos[index].codigo  == codigo)
        return true;
    }

    return false;
  };

  function validaCantidad(insumos){
    var index;

    for( index in insumos){
      if( !insumos[index].cantidad || insumos[index].cantidad  < 0 )
        return false;
    }

    return true;
  }

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({
        'id': $scope.insumos[index].id,
        'cantidad':$scope.insumos[index].cantidad,
        'fecha': dataForamat($scope.insumos[index].fecha),
        'lote':$scope.insumos[index].lote
      });
    }

    return insumos;
  }

  function dataForamat(data){

    if(data != null){

      var month = data.getMonth() + 1;
      var day = data.getDate();

      if( day < 10 )
        day = "0"+day;

      if(month < 10)
        month = "0"+month;

      return data.getFullYear() + '-' + month + '-' + day;
    }
  }

  $scope.restablecer = function(){
    $scope.insumos  = [];
    $scope.alert = {};
    $scope.documentoSelect = {};
    $scope.terceroSelect   = {};
    $scope.panelTerceros = false;
    $scope.insumoSelect = {};
  }

  var parseDocumento = function(){
    if($scope.documentoSelect.hasOwnProperty('selected'))
      return $scope.documentoSelect.selected.id;

    return '';
  }

  var parseTercero = function(){
    if($scope.terceroSelect.hasOwnProperty('selected'))
      return $scope.terceroSelect.selected.id;

    return '';
  }

  $scope.searchTerceros = function(){
    if($scope.documentoSelect.hasOwnProperty('selected')){
      $scope.terceros = [];
      $scope.terceroSelect = {};

      if($scope.documentoSelect.selected.tipo != "interno"){
        $http.get('/administracion/almacenes/terceros/'+ $scope.documentoSelect.selected.tipo)
          .success(function(response){
            $scope.terceros = response;
            $scope.panelTerceros = true;
          });
      }
      else{
        $scope.panelTerceros = false;
      }
    }
  }

});

angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});
