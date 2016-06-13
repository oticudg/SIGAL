"use strict";

angular.module('deposito').
controller('kardexController',function($scope,$http,$modal){

	$scope.movimientos = [];
	$scope.cRegistro   = '5';
	$scope.insumoInfo  = insumoKardex;

	var obtenerKardex = function(filter){

		if(filter){
			filter.insumo = insumoKardex.id;
			filter.dateI  = filter.dateI ? filter.dateI : insumoKardex.dateI;
			filter.dateF  = filter.dateF ? filter.dateF : insumoKardex.dateF;
		}
		else{
			var filter = {
		    'insumo':insumoKardex.id,
				'dateI':insumoKardex.dateI,
				'dateF':insumoKardex.dateF
		  }
	  }

		$scope.insumoInfo = {
			'insumo':filter.insumo,
			'dateI' :filter.dateI,
			'dateF' :filter.dateF
		};

		$http.post('/inventario/kardex/getKardex',filter)
			.success( function(response){
				$scope.movimientos = response.kardex;
			});
	};

	$scope.filterPanel = function(){
		$scope.barSearch = $scope.barSearch ? false:true;
		$scope.filtro = {};
	}

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

	$scope.search = function(){

		var modalInstance = $modal.open({
			animation: true,
			templateUrl: '/inventario/kardex/search',
			controller: 'searchKardexCtrl',
			windowClass:'large-Modal',
			resolve: {
				 obtenerKardex:function() {
						return obtenerKardex;
				 }
		 }
		});
	};

	$scope.update = function(){
		obtenerKardex();
	}

	obtenerKardex();
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


angular.module('deposito').controller('searchKardexCtrl', function ($scope, $modalInstance, $http, obtenerKardex){

	$scope.data = {};
	$scope.data.type  = "all";
  $scope.insumoSelect = {};
	$scope.userSelect   = {};
	$scope.documentoSelect = {};
	$scope.documentos = [];

	$http.get('/getUsuariosDeposito')
		.success(function(response){
			var usuarios = response;
			var userSet = [];

			for(var index in usuarios){

				 var usuario ={
					 'nombre': usuarios[index].nombre + ' ' +usuarios[index].apellido,
					 'id'		 : usuarios[index].id
				 }
				 userSet.push(usuario);
			}

			$scope.usuarios = userSet;
		});

	$http.get('/documentos/all')
      .success( function(response){ $scope.documentos = response;});

	$scope.refreshInsumos = function(insumo) {
		$scope.searchAjax = true;
			var params = {insumo: insumo};
			return $http.get(
				'/inventario/getInsumosInventario',
				{params: params}
			).then(function(response){
				$scope.listInsumos =  response.data
			});
		};

	$scope.buscar = function(){
		parseAmount();
		parseUser();
		parseDate();
		parseTime();
		parseDocumento();

		obtenerKardex($scope.data);
		$modalInstance.dismiss('cancel');
	}

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

	$scope.insumoSearch = function () {
		$scope.insumop = $scope.insumop ? false:true;
	};

	$scope.dateSearch = function(){
		$scope.datep = $scope.datep ? false:true;
	}

	$scope.timeSearch = function(){
		$scope.timep = $scope.timep ? false:true;
		$scope.timeI = new Date();
		$scope.timeF = new Date();
	}

	$scope.amounSearch = function(){
		$scope.amounp = $scope.amounp ? false:true;
	}

	$scope.amounESearch = function(){
		$scope.amounEp = $scope.amounEp ? false:true;
	}

	$scope.openI = function($event) {
			$event.preventDefault();
			$event.stopPropagation();

			$scope.openedI = true;
		};

	$scope.openF = function($event) {
		$event.preventDefault();
		$event.stopPropagation();

		$scope.openedF = true;
	};

	$scope.moviType = function(type){

		$scope.documentos = [];
		$scope.documentoSelect = {};

		switch(type){

			case "entrada":
				$http.get('/documentos/all/entradas')
						.success( function(response){ $scope.documentos = response;});
			break;

			case "salida":
				$http.get('/documentos/all/salidas')
						.success( function(response){ $scope.documentos = response;});
			break;

			case "all":
				$http.get('/documentos/all')
						.success( function(response){ $scope.documentos = response;});
			break;

		}
	}

	var parseAmount = function(){
		if($scope.data.cantidadI && $scope.data.cantidadF)
				$scope.data.moveRange = true;

		if($scope.data.existenciaF != null && $scope.data.existenciaF != null)
				$scope.data.existRange = true;
	}

	var parseUser = function(){
		if($scope.userSelect.hasOwnProperty('selected'))
			$scope.data.user = $scope.userSelect.selected.id;
	}

	var parseDate = function(){
		if($scope.datep && $scope.fechaI && $scope.fechaF){
			$scope.data.dateI = dateForamat($scope.fechaI);
			$scope.data.dateF = dateForamat($scope.fechaF);
			$scope.data.dateranger = true;
		}
	}

	var parseTime = function(){
		if($scope.timep){
				$scope.data.horaI = timeFormat($scope.timeI);
				$scope.data.horaF = timeFormat($scope.timeF);
				$scope.data.hourrange = true;
		}
	}

	var parseDocumento = function(){
		if($scope.documentoSelect.hasOwnProperty('selected'))
			$scope.data.concep = $scope.documentoSelect.selected.id;
	}

	function dateForamat(date){

		if(date != null){

			var month = date.getMonth() + 1;
			var day = date.getDate();

			if(month < 10)
				month = "0"+month;

			return day + '/' + month + '/' + date.getFullYear();
		}
	}

	function timeFormat(time){

		var hour = time.getHours();
		var minute = time.getMinutes();

		if( hour < 10 )
			hour = "0" + hour;

		if(minute < 10)
		 	minute = "0" + minute;

		return  hour + '-' + minute;
	}

});
