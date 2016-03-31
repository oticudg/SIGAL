"use strict";

angular.module('deposito').
controller('alertController',function($scope,$http){
 
  $scope.insumoSelect = {};  
  $scope.listInsumos = [];
  $scope.insumos = [];

  $scope.refreshInsumos = function(insumo){

    var params = {insumo: insumo};
    return $http.get(
      '/inventario/herramientas/getInventarioAlert',
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
        'med':$scope.insumoSelect.selected.med
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
  		'med':$scope.insumos[index].med});
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
          !Number.isInteger($scope.insumos[index].min))
        return false;

      if( !$scope.insumos[index].med || $scope.insumos[index].med <= 0 || 
          !Number.isInteger($scope.insumos[index].med))
        return false;

      if( $scope.insumos[index].min >= $scope.insumos[index].med )
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

  	if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique un valor valido para cada insumo"};
      return;
    }

  	var data = {
  		'insumos':empaquetaData()
  	};

  	$http.post('/inventario/herramientas/estableceAlarmas', data)
  	 .success(
  	 	function(response){

			$scope.alert = {type:response.status , msg:response.menssage};  	 	

	  	 	if( response.status == 'success'){

	  	 		$scope.alert = {type:response.status , msg:response.menssage};
	  	 		restablecer();
	  		}
	  	}
  	);
  }
})

.controller('cargaInvController',function($scope,$http, $modal){

  $scope.insumoSelect = {};  
  $scope.departamentos = [];
  $scope.departamento;
  $scope.insumos = [];
  $scope.listInsumos = [];
  $scope.alert = {
      type:"warning", 
      msg:'Precaucion! la carga de inventario eliminara todos los insumos cargados previamente.'
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
        'descripcion':$scope.insumoSelect.selected.descripcion
      }
    );
    
    $scope.insumoSelect = {};
  }

  $scope.closeAlert = function(){
    $scope.alert = {};
  };

  $scope.thereInsumos = function(){
    return $scope.insumos.length > 0 ? true:false;
  };

  $scope.eliminarInsumo = function(index){
    $scope.insumos.splice(index, 1);
  };

  function insumoExist(codigo, insumos){

    var index;

    for(index in insumos){
      if(insumos[index].codigo  == codigo)
        return true;
    }

    return false;
  };

  function empaquetaData(insumosOri){

    var index;
    var insumos = [];

    for( index in insumosOri){
      insumos.push({'id': insumosOri[index].id, 'cantidad':insumosOri[index].cantidad});
    }

    return insumos;
  }

  function validaCantidad(insumos){
    var index;

    for( index in insumos){
      if( !insumos[index].cantidad || insumos[index].cantidad  < 0 || 
          !Number.isInteger($scope.insumos[index].cantidad))
        return false;
    }

    return true;
  }

  $scope.restablecer = function(){
    $scope.insumos  = [];
    $scope.alert = {};
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
      'insumos' : empaquetaData($scope.insumos)
    };

    $http.post('/inventario/herramientas/cargaInventario', data)
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

})

.controller('listCargasController',function($scope,$http, $modal){
  
  $scope.entradas = [];
  $scope.cRegistro = '5';

  $http.get('/inventario/herramientas/getInventarioCargas')
          .success( function(response){$scope.entradas = response});

  $scope.detallesCarga = function(index){
    var modalInstance = $modal.open({
      animation: true,
          templateUrl: '/inventario/herramientas/detallesCarga',
          controller: 'detallesCargaCtrl',
          windowClass: 'large-Modal',
          resolve: {
             id:function () {
                return index;
             }
         }
    });
  };

});

angular.module('deposito').controller('detallesCargaCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.entrada = {};
  $scope.insumos = [];
  $scope.visibility = false;

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  
  };

  $scope.chvisibility = function(){
    $scope.search = {};
    $scope.visibility =  !$scope.visibility ? true:false;
  }

  $http.get('/inventario/herramientas/getInventarioCarga/' + id)
    .success(function(response){

      $scope.entrada = response.entrada;
      $scope.insumos = response.insumos;
  });

});


angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});