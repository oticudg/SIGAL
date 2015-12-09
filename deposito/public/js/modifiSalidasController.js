"use strict";

angular.module('deposito').
controller('modifiSalidasController',function($scope,$http,$modal){

	$scope.salidas = [];
  $scope.cRegistro = '5';

	$scope.obtenerSalidas = function(){

		$http.get('/modificaciones/getSalidas')
			.success( function(response){$scope.salidas = response});
	};

	$scope.registrarModificacion = function() {

      $modal.open({
     		animation: true,
      		templateUrl: '/modificaciones/registrarSalida',
      		windowClass: 'large-Modal',
      		controller: 'registraModificacionCtrl',
      		resolve: {
       			 obtenerSalidas: function () {
          			return $scope.obtenerSalidas;
        		 }
      		}
	    });
	}

	$scope.detallesModificacion = function(index){

	    var modalInstance = $modal.open({

	      animation: true,
	          templateUrl: '/modificaciones/detallesSalida',
	          controller: 'detallesModificacionCtrl',
	          windowClass: 'large-Modal',
	          resolve: {

	             id:function () {
	                return index;
	             }
	         }
	    });
  	};

  	$scope.obtenerSalidas();

});

angular.module('deposito').controller('registraModificacionCtrl', 
	function ($scope, $modalInstance, $http, obtenerSalidas){

  $scope.btnVisivilidad = true;
  $scope.alert = {};
  $scope.codigo = '';
  $scope.orden = '';
  $scope.departamento = '';
  $scope.salida = {};
  $scope.insumos = [];
  $scope.status = false;
  $scope.departamentos = [];

  $http.get('/getDepartamentos')
    .success( function(response){ $scope.departamentos = response;});

  $scope.registrar = function () {
  	$scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(){

    $scope.alert = {};

  };

  $scope.ubicarSalida = function(){

  	if($scope.codigo == ''){
  		$scope.alert = {'type':'danger' , 'msg':'Espefifique un codigo de Pro-Forma'};
  	}
  	else{

  		$http.get('/getSalidaCodigo/' + $scope.codigo)
  			.success(
  				function(response){

  					if( response.status == 'danger'){
						$scope.alert = {'type':response.status , 'msg':response.menssage};					
  						return;
  					}
  					else{

  						$scope.salida = response.salida;
  						$scope.insumos = response.insumos;
  						$scope.alert = {};
  						$scope.status = true;
  						return;
  					}
  				}
  		);
  	}

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

  function serializeInsumos(){

  	var insumos = [];
  	var index; 

  	for( index in $scope.insumos){

  		insumos.push(
        {
         'id':$scope.insumos[index].id, 
  			 'solicitado':$scope.insumos[index].Msolicitado, 
         'despachado':$scope.insumos[index].Mdespachado
      });
  	}

  	return insumos;

  }

  $scope.save = function(){

   	var $data = {

   		'salida'	      : $scope.salida.id,
  		'departamento'	: $scope.departamento,
  		'insumos'	      : serializeInsumos()
 	};

    $http.post('/modificaciones/registrarSalida', $data)
      .success(function(response){

    		$scope.alert = {};

        if(response.status == 'unexist'){

          marcaInsumos(response.data);
          $scope.alert = {type:'danger', 
            msg:'La cantidad de los insumos marcados no puede ser modificada por este monto, por favor verifique el inventario'};
          return;
        }
    	  
        $scope.alert = {"type":response.status , "msg":response.menssage};

        if( response.status == "success"){
          $scope.btnVisivilidad = false; 
          obtenerSalidas();
        }
        
   	});

 };

});

angular.module('deposito').controller('detallesModificacionCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.salida = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  
  };

  $scope.detalles = function(){

    $http.get('/modificaciones/getSalida/' + id)
      .success(function(response){

      	$scope.modificacion = response.modificacion;
        $scope.salida = response.salida;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});
