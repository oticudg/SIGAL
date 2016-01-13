"use strict";

angular.module('deposito').
controller('registroEntradaController',function($scope, $http ,$modal){

  $scope.insumoSelect = {};  
  $scope.provedores = [];
  $scope.departamentos = [];
  $scope.departamento;
  $scope.provedor;
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
        '/getInsumosConsulta',
        {params: params}
      ).then(function(response){
        $scope.listInsumos =  response.data
      });
  };

  $http.get('/getProvedores')
    .success( function(response){ $scope.provedores = response;});

  $http.get('/getDepartamentos')
    .success( function(response){ $scope.departamentos = response;});


  $scope.agregarInsumos = function(){

    if(!$scope.insumoSelect.selected){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }
      
    if( insumoExist($scope.insumoSelect.selected.codigo, $scope.insumos) ){
      $scope.alert = {type:"danger" , msg:"Este insumo ya se ha agregado en esta entrada"};
      return; 
    }

    $scope.insumos.push(
      {
        'id':$scope.insumoSelect.selected.id, 
        'codigo':$scope.insumoSelect.selected.codigo, 
        'descripcion':$scope.insumoSelect.selected.descripcion,
        'dI':null
      }
    );
    
    $scope.insumoSelect = {};
  }

  $scope.registrar = function(datos){

    if( !validaCantidad($scope.insumos) ){
      $scope.alert = {type:"danger" , msg:"Especifique un valor valido para cada insumo"};
      return;
    }

    switch(datos){
      
      case 'orden':
        
        var data = {
          'orden'  : $scope.orden,
          'provedor': $scope.provedor,
          'insumos' : empaquetaData($scope.insumos)
        };

        var origen = '/entradas/registrar/orden';
      break;

      case 'donacion':

        var data = {
          'provedor': $scope.provedor,
          'insumos' : empaquetaData($scope.insumos)
        };

        var origen = '/entradas/registrar/donacion';
      break;

      case 'devolucion':

        var data = {
          'departamento': $scope.departamento,
          'insumos' : empaquetaData($scope.insumos)
        };

        var origen = '/entradas/registrar/devolucion';
      break;

    }

    console.log(data);

    $http.post(origen, data)
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
      if( !insumos[index].cantidad || insumos[index].cantidad  < 0 || 
          !Number.isInteger($scope.insumos[index].cantidad))
        return false;
    }

    return true;
  }

  function empaquetaData(insumosOri){

    var index;
    var insumos = [];

    for( index in insumosOri){
      insumos.push({
        'id': insumosOri[index].id, 
        'cantidad':insumosOri[index].cantidad,
        'fecha': dataForamat(insumosOri[index].fecha),
        'lote':insumosOri[index].lote
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
    $scope.orden   = '';
    $scope.departamento = '';
    $scope.provedor = '';
    $scope.alert = {};
  }

});

angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});