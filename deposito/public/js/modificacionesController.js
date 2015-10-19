"use strict";

angular.module('deposito').
controller('modificacionesController',function($scope,$http,$modal){

	$scope.entradas = [];
  	$scope.cRegistro = '5';

	$scope.obtenerEntradas = function(){

		$http.get('/getEntradasModificadas')
			.success( function(response){$scope.entradas = response});
	};

	$scope.registrarModificacion = function() {

      $modal.open({
     		animation: true,
      		templateUrl: '/registrarModificacionEntrada',
      		size:'lg',
      		controller: 'registraModificacionCtrl',
      		resolve: {
       			 obtenerEntradas: function () {
          			return $scope.obtenerEntradas;
        		 }
      		}
	    });
	}

	$scope.detallesModificacion = function(index){

	    var modalInstance = $modal.open({

	      animation: true,
	          templateUrl: '/detallesEntradaModificada',
	          controller: 'detallesModificacionCtrl',
	          size:'lg',
	          resolve: {

	             id:function () {
	                return index;
	             }
	         }
	    });
  	};

  	$scope.obtenerEntradas();

});

angular.module('deposito').controller('registraModificacionCtrl', 
	function ($scope, $modalInstance, $http, obtenerEntradas){

  $scope.btnVisivilidad = true;
  $scope.alerts = [];
  $scope.codigo = '';
  $scope.orden = '';
  $scope.provedor = '';
  $scope.entrada = {};
  $scope.insumos = [];
  $scope.status = false;
  $scope.provedores = [];

  $http.get('/getProvedores')
    .success( function(response){ $scope.provedores = response;});

  $scope.registrar = function () {
  	$scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

  $scope.ubicarEntrada = function(){

  	if($scope.codigo == ''){
  		$scope.alerts.push( {'type':'danger' , 'msg':'Espefifique un codigo de Pro-Forma'});
  	}
  	else{

  		$http.get('getEntradaCodigo/' + $scope.codigo)
  			.success(
  				function(response){

  					if( response.status == 'danger'){
						$scope.alerts.push( {'type':response.status , 'msg':response.menssage});					
  						return;
  					}
  					else{

  						$scope.entrada = response.entrada;
  						$scope.insumos = response.insumos;
  						$scope.alerts = [];
  						$scope.status = true;
  						return;
  					}
  				}
  		);
  	}

  }

  function serializeInsumos(){

  	var insumos = [];
  	var index; 

  	for( index in $scope.insumos){

  		insumos.push({'id':$scope.insumos[index].id, 
  			'modificacion':$scope.insumos[index].modificacion});
  	}

  	return insumos;

  }

  $scope.save = function(){

   	var $data = {

   		'entrada'	: $scope.entrada.id,
  		'orden'		: $scope.orden,
  		'provedor'	: $scope.provedor,
  		'insumos'	: serializeInsumos()
 	};

    $http.post('registrarModificacionEntrada', $data)
      .success(function(response){

    		$scope.alerts = [];
    	  	$scope.alerts.push( {"type":response.status , "msg":response.menssage});
     
          	$scope.btnVisivilidad = ( response.status == "success") ? false : true; 
          	obtenerEntradas();
   	});

 };

});

angular.module('deposito').controller('detallesModificacionCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.entrada = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  
  };

  $scope.detalles = function(){

    $http.get('/getEntradasModificada/' + id)
      .success(function(response){

      	$scope.modificacion = response.modificacion;
        $scope.entrada = response.entrada;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});
