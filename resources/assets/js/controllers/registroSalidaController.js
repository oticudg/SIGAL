"use strict";

angular.module('deposito').
controller('registroSalidaController',function($scope,$http,$modal){

  $scope.insumoSelect = {};
  $scope.terceroSelect = {};
  $scope.documentoSelect = {};
  $scope.terceros = [];
  $scope.documentos = [];
  $scope.listInsumos = [];
  $scope.insumos = [];
  $scope.alert = {};
  $scope.cRegistro = '10';

  $scope.refreshInsumos = function(insumo) {
    var params = {insumo: insumo};
    return $http.get(
      '/inventario/getInsumosInventario',
      {params: params}
    ).then(function(response){
      $scope.listInsumos =  response.data
    });
  };

  $http.get('/administracion/documentos/all/salidas')
      .success( function(response){ $scope.documentos = response;});

  $scope.agregarInsumos = function(){

    if(!$scope.insumoSelect.selected){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }

    $scope.insumos.unshift(
      {
        'id':$scope.insumoSelect.selected.id,
        'codigo':$scope.insumoSelect.selected.codigo,
        'descripcion':$scope.insumoSelect.selected.descripcion
      }
    );

    $scope.insumoSelect = {};
  }

  $scope.registrar = function(){

    if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique valores validos para cada insumo"};
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

  var save = function($data){

    var $data = {
      'documento': parseDocumento(),
      'tercero' : parseTercero(),
      'insumos'  : empaquetaData()
    };

    $http.post('/transferencias/salidas/registrar', $data)
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

            restablecer();
            return;
          }

          desmarcarInsumos();

          if(response.status == 'unexist'){

            marcaInsumos(response.data);
            $scope.alert = {type:'danger', msg:response.message};
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

  function validaCantidad(){
    var index;

    for( index in $scope.insumos){
      if( !$scope.insumos[index].despachado || $scope.insumos[index].despachado < 0 ||
          $scope.insumos[index].solicitado < $scope.insumos[index].despachado)
        return false;
    }

    return true;
  }

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({'id': $scope.insumos[index].id, 'solicitado':$scope.insumos[index].solicitado,
        'despachado':$scope.insumos[index].despachado, 'lote':$scope.insumos[index].lote});
    }

    return insumos;
  }

  function marcaInsumos(insumos){
    var index1;
    var index2;

    for(index1 in $scope.insumos){
      for(index2 in insumos){
        if($scope.insumos[index1].id == insumos[index2].insumo && $scope.insumos[index1].lote == insumos[index2].lote){
          $scope.insumos[index1].style = 'danger';
          break;
        }
      }
    } 
  }

  function desmarcarInsumos(){
    var index;

    for(index in $scope.insumos){
      $scope.insumos[index].style = '';
    }
  }

  function restablecer(){
    $scope.insumos  = [];
    $scope.terceros  = [];
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

});

angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});
