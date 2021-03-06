"use strict";

angular.module('deposito').
controller('modificacionesController',function($scope,$http,$modal){

	$scope.modificaciones = [];
  $scope.cRegistro = '10';

	$scope.obtenerModificaciones = function(){

		$http.get('/inventario/modificaciones/getModificaciones')
			.success( function(response){$scope.modificaciones = response});
	};

	$scope.registrarModificacion = function() {

      $modal.open({
     		animation: true,
    		templateUrl: '/inventario/modificaciones/registrar',
    		windowClass: 'large-Modal',
    		controller: 'registraModificacionCtrl',
    		resolve: {
     			 obtenerModificaciones: function () {
        			return $scope.obtenerModificaciones;
      		 },
					 detallesNota:function(){
						 return detallesNota;
					 }
    		}
	    });
	}

	$scope.detallesModificacion = function(index){

	    var modalInstance = $modal.open({

	      animation: true,
	          templateUrl: '/inventario/modificaciones/detalle',
	          controller: 'detallesModificacionCtrl',
	          windowClass: 'large-Modal',
	          resolve: {

	             id:function () {
	                return index;
	             },
							 detallesNota:function(){
								 return detallesNota;
							 }
	         }
	    });
  	};

		var detallesNota = function(type,index){

			if(type == "entrada"){
				var search ={
					view:"/inventario/entradas/detalles",
					data:"/inventario/entradas/getEntrada/",
					id:index
				}
			}
			else{
				var search = {
					view:"/inventario/salidas/detallesSalida",
					data:"/inventario/salidas/getSalida/",
					id:index
				}
			}

			var modalInstance = $modal.open({
						animation: true,
						templateUrl: search.view,
						controller: 'detallesNotaCtrl',
						windowClass: 'large-Modal',
						resolve: {
	             search:function() {
	                return search;
	             }
	         }
			});
		};

		$scope.search = function(){
			$scope.busqueda = {};
			$scope.barSearch = $scope.barSearch ? false:true;
		}

  	$scope.obtenerModificaciones();

});

angular.module('deposito').controller('registraModificacionCtrl',
	function ($scope, $modalInstance, $http, obtenerModificaciones, $modal, detallesNota){

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

						$http.get('/administracion/documentos/all/' + response.data.type)
					      .success( function(response){ $scope.documentos = response;});

						if(response.data.tercero == 'interno'){
							$scope.panelTerceros = false;
						}
						else{
							$http.get('/administracion/almacenes/terceros/'+ response.data.tercero)
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

	$scope.update = function(){

		registerUpdate.update_tercero = parseTercero();
		registerUpdate.update_documento = parseDocumento();

		$http.post('/inventario/modificaciones/registrar',registerUpdate)
			.success(function(response){
				$scope.alert = {'type':response.status, 'msg':response.message};

				if(response.status == 'success'){
					$scope.uiStatus = false;
					obtenerModificaciones();
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

	$scope.detallesNota = detallesNota;

});

angular.module('deposito').controller('detallesModificacionCtrl', function ($scope, $modalInstance, $http, id, detallesNota) {

  $scope.entrada = {};
  $scope.insumos = [];

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');

  };

  var detalles = function(){

    $http.post('/inventario/modificaciones/getModificacion/' + id)
      .success(function(response){
      	$scope.movimiento = response.movimiento;
				$scope.modificacion = response.modificacion;
    });
  };

	$scope.detallesNota = detallesNota;

  detalles(id);

});

angular.module('deposito').controller('detallesNotaCtrl', function ($scope, $modalInstance, $http,search){

  $scope.insumos = [];
  $scope.visibility = false;

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');

  };

  $scope.chvisibility = function(){
    $scope.search = {};
    $scope.visibility =  !$scope.visibility ? true:false;
  }

  $scope.detalles = function(){

    $http.get(search.data + search.id)
      .success(function(response){
        $scope.nota = response.nota;
        $scope.insumos = response.insumos;
    });
  };

  $scope.detalles();

});
