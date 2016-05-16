"use strict";

angular.module('deposito').
controller('salidasController',function($scope,$http,$modal){

	$scope.salidas = [];
  $scope.indice = 'Pro-Formas';
  $scope.salidasInsumos = [];
  $scope.cRegistro = '5';
  $scope.status = true;

	$scope.obtenerSalidas = function(type,query){

		if(type == 'search'){
			$http.get('/getSearch', query)
				.success( function(response){$scope.salidas = response});
		}
		else{
			$http.get('/getSalidas')
				.success( function(response){$scope.salidas = response});
		}
	};

  $scope.obtenerSalidasInsumos = function(){

    $http.get('/getInsumosSalidas')
      .success( function(response){$scope.salidasInsumos = response});
  };

  $scope.registrosProformas = function(){
    $scope.busqueda = '';
    $scope.indice = 'Pro-Formas';
		$scope.salidasInsumos = [];
    $scope.status = true;
    $scope.obtenerSalidas();
  };

  $scope.registrosInsumos = function(){
    $scope.busqueda = '';
    $scope.indice = 'Insumos';
		$scope.insumos = [];
		$scope.status = false;
    $scope.obtenerSalidasInsumos();
  };

  $scope.detallesSalida = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/detallesSalida',
          controller: 'detallesSalidaCtrl',
          windowClass: 'large-Modal',
          resolve: {

             id:function () {
                return index;
             }
         }
    });
  };

	$scope.search = function(){

		$scope.status = true;

		var modalInstance = $modal.open({
			animation: true,
					templateUrl: '/search',
					controller: 'serarchSalidaCtrl',
					windowClass: 'large-Modal',
					resolve: {
						 obtenerSalidas:function (){
								return $scope.obtenerSalidas;
						 }
					}
		});
	};

	$scope.obtenerSalidas();

});

angular.module('deposito').controller('detallesSalidaCtrl', function ($scope, $modalInstance, $http, id) {

  $scope.salida = {};
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

    $http.get('/getSalida/' + id)
      .success(function(response){

        $scope.nota = response.nota;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});

angular.module('deposito').controller('serarchSalidaCtrl', function ($scope, $modalInstance, $http, obtenerSalidas){

	$scope.data = {};
	$scope.insumoSelect = {};
	$scope.departSelect = {};
	$scope.userSelect   = {};
	$scope.data.orden = "desc";

	$http.get('getUsuariosDeposito')
		.success(function(response){$scope.usuarios = response});

	$http.get('/getDepartamentos')
      .success( function(response){ $scope.departamentos = response;});

	$scope.refreshInsumos = function(insumo) {

    var params = {insumo: insumo};
    return $http.get(
      'inventario/getInsumosInventario',
      {params: params}
    ).then(function(response){
      $scope.listInsumos =  response.data
    });
  };

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

	$scope.buscar = function(){

		parseDepart();
		parseUser();
		parseInsumo();
		parseAmount();
		parseDate();
		parseTime();

		var config = {
			params:$scope.data
		};

		obtenerSalidas('search',config);
		$modalInstance.dismiss('cancel');
	}

	var parseDepart = function(){
		if($scope.departSelect.hasOwnProperty('selected'))
			$scope.data.depart = $scope.departSelect.selected.id;
	}

	var parseInsumo = function(){
		if($scope.insumop && $scope.insumoSelect.hasOwnProperty('selected'))
			$scope.data.insumo = $scope.insumoSelect.selected.id;
	}

	var parseAmount = function(){
		if($scope.data.cantidadI && $scope.data.cantidadF)
				$scope.data.amountrange = true;
  }

	var parseUser = function(){
		if($scope.userSelect.hasOwnProperty('selected'))
			$scope.data.user = $scope.userSelect.selected.id;
	}

	var parseDate = function(){
		if($scope.datep && $scope.fechaI && $scope.fechaF){
			$scope.data.fechaI = dateForamat($scope.fechaI);
			$scope.data.fechaF = dateForamat($scope.fechaF);
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

	function dateForamat(data){

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
