"use strict";

angular.module('deposito').
controller('kardekController',function($scope,$http,$modal){

	$scope.movimientos = [];
	$scope.cRegistro = '5';
  $scope.insumo;

	$scope.obtenerKardek = function(){

    var data = {
      'insumo':insumo
    }

		$http.post('/inventario/kardek',data)
			.success( function(response){
				$scope.movimientos = response.kardek;
				$scope.insumo = response.insumo;
			});
	};

	$scope.detallesNota = function(type,index,inv){

		if(type == "entrada"){
			if(inv){
				var search = {
					view:"/inventario/herramientas/detallesCarga",
					data:"/inventario/herramientas/getInventarioCarga/",
					id:index
				}
			}
			else{
				var search ={
					view:"/entradas/detalles",
					data:"/entradas/getEntrada/",
					id:index
				}
			}
		}
		else{
			var search = {
				view:"/detallesSalida",
				data:"/getSalida/",
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

	$scope.obtenerKardek();
});

angular.module('deposito').controller('detallesNotaCtrl', function ($scope, $modalInstance, $http,search) {

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
