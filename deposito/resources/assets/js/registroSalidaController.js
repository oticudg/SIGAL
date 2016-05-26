"use strict";

angular.module('deposito').
controller('registroSalidaController',function($scope,$http,$modal){

  $scope.insumoSelect = {};
  $scope.departamentos = [];
  $scope.documentos = [];
  $scope.listInsumos = [];
  $scope.servSelect = {};
  $scope.documentoSelect = {};
  $scope.insumos = [];
  $scope.alert = {};

  $scope.refreshInsumos = function(insumo) {

    var params = {insumo: insumo};
    return $http.get(
      'inventario/getInsumosInventario',
      {params: params}
    ).then(function(response){
      $scope.listInsumos =  response.data
    });
  };

  $http.get('/documentos/all/salidas')
      .success( function(response){ $scope.documentos = response;});

  $http.get('/getDepartamentos')
      .success( function(response){ $scope.departamentos = response;});

  $scope.agregarInsumos = function(){

    if(!$scope.insumoSelect.selected){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }

    if( insumoExist($scope.insumoSelect.selected.codigo) ){
      $scope.alert = {type:"danger" , msg:"Este insumo ya se ha agregado en esta entrada"};
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
      'departamento': parseServ(),
      'insumos'     : empaquetaData()
    };

    $http.post('/registrarSalida', $data)
      .success(
        function(response){

          $scope.loader = false;

          if(response.status == 'unexist'){

            marcaInsumos(response.data);
            $scope.alert = {type:'danger', msg:'La cantidad de los insumos marcados son insuficientes'};
            return;
          }

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

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({'id': $scope.insumos[index].id, 'solicitado':$scope.insumos[index].solicitado,
        'despachado':$scope.insumos[index].despachado});
    }

    return insumos;
  }

  function marcaInsumos(ids){
    var index;
    var id;

    for(index in $scope.insumos){
      $scope.insumos[index].style = '';
    }

    for( id in ids){
      for(index = 0; index < $scope.insumos.length; index++)

        if($scope.insumos[index].id == ids[id] ){
          $scope.insumos[index].style = 'danger';
          break;
        }
    }
  }

  function restablecer(){
    $scope.insumos  = [];
    $scope.alert = {};
    $scope.servSelect = {};
  }

  var parseServ = function(){
		if($scope.servSelect.hasOwnProperty('selected'))
		  return $scope.servSelect.selected.id;

    return '';
	}

});

angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});
