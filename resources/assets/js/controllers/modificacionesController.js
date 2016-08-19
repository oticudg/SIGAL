"use strict";

angular.module('deposito').
controller('modificacionesController',function($scope,$http,$modal){

	$scope.entradas = [];
  $scope.cRegistro = '5';

	$scope.obtenerEntradas = function(){

		$http.get('/modificaciones/getEntradas')
			.success( function(response){$scope.entradas = response});
	};

	$scope.registrarModificacion = function() {

      $modal.open({
     		animation: true,
    		templateUrl: '/inventario/modificaciones/registrar',
    		windowClass: 'large-Modal',
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
	          templateUrl: '/modificaciones/detallesEntrada',
	          controller: 'detallesModificacionEntradaCtrl',
	          windowClass: 'large-Modal',
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

  $scope.uiStatus =	false;
	$scope.documentos = [];
	$scope.terceros = [];
	$scope.documentoSelect = {};
	$scope.terceroSelect = {};
	$scope.panelTerceros = true;
  $scope.alert = {};
  $scope.code = '';
	var registerUpdate = {};

  $scope.registrar = function () {
  	$scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(){
    $scope.alert = {};
  };

	$scope.search = function(){
			$http.post('/inventario/modificaciones/getMovimiento',{'code':$scope.code})
				.success(function(response){
					if( response.status != 'success'){
						$scope.alert = {'type':response.status, 'msg':response.message};
					}
					else{

						$http.get('/documentos/all/' + response.data.type)
					      .success( function(response){ $scope.documentos = response;});

						if(response.data.tercero == 'interno'){
							$scope.panelTerceros = false;
						}
						else{
							$http.get('/depositos/terceros/'+ response.data.tercero)
			          .success(function(response){
			            $scope.terceros = response;
			          });
						}

						registerUpdate = {
							'movimiento':response.data.movimiento.id,
							'documento':response.data.documento
						}

						$scope.movimiento = response.data.movimiento;
						$scope.uiStatus = true;

					}
				});
	}

  $scope.searchTerceros = function(){
    if($scope.documentoSelect.hasOwnProperty('selected')){
      $scope.terceros = [];
      $scope.terceroSelect = {};

      if($scope.documentoSelect.selected.tipo != "interno"){
        $http.get('/depositos/terceros/'+ $scope.documentoSelect.selected.tipo)
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

	$scope.update = function(){

		registerUpdate.update_tercero = parseTercero();
		registerUpdate.update_documento = parseDocumento();

		$http.post('/inventario/modificaciones/registrar',registerUpdate)
			.success(function(response){
				$scope.alert = {'type':response.status, 'msg':response.message};

				if(response.status == 'success'){
					$scope.uiStatus = false;
					restart();
				}

			});
	};

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

	var restart = function(){
		$scope.documentoSelect = {};
		$scope.terceroSelect = {};
		$scope.code = '';
	}

});

angular.module('deposito').controller('detallesModificacionEntradaCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.entrada = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');

  };

  $scope.detalles = function(){

    $http.get('/modificaciones/getEntradas/' + id)
      .success(function(response){

      	$scope.modificacion = response.modificacion;
        $scope.entrada = response.entrada;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});
