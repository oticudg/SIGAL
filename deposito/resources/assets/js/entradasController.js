"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradas = [];
  $scope.indice  = 'Pro-Formas';
  $scope.insumos = [];
  $scope.insumosDon = [];
  $scope.cRegistro = '5';
  $scope.orden = {};
  $scope.insumos = [];
  $scope.uiStatus = {
    'proformas' : true,
    'insumos'   : false,
    'ordenes'   : false
  };

	var obtenerEntradas = function(datos, query){

    switch(datos){

      case 'orden':
        $http.get('/entradas/getEntradas/'+ datos)
    			.success( function(response){$scope.entradas = response});
      break;

      case 'donacion':
        $http.get('/entradas/getEntradas/'+ datos)
          .success( function(response){$scope.entradas = response});
      break;

      case 'devolucion':
        $http.get('/entradas/getEntradas/'+ datos)
          .success( function(response){$scope.entradas = response});
      break;

      case 'toda':
        $http.get('/entradas/getEntradas')
          .success( function(response){$scope.entradas = response});
      break;

      case 'search':
        $http.get('/entradas/getSearch', query)
          .success( function(response){$scope.entradas = response});
      break;
    }

  };

  var obtenerInsumos = function(datos){

      switch(datos){

        case 'orden':
          $http.get('/entradas/getInsumos/'+ datos)
            .success( function(response){$scope.insumos = response});
        break;

        case 'donacion':
          $http.get('/entradas/getInsumos/'+ datos)
            .success( function(response){$scope.insumos = response});
        break;

        case 'devolucion':
          $http.get('/entradas/getInsumos/'+ datos)
            .success( function(response){$scope.insumos = response});
        break;

        case 'todo':
          $http.get('/entradas/getInsumos')
            .success( function(response){$scope.insumos = response});
        break;
      }
  };

  $scope.registrosEntradas = function(datos){

    $scope.busqueda = '';
    $scope.indice = 'Pro-Formas';
    visivility(1);

    switch(datos){

      case 'ordenes':
        obtenerEntradas('orden');
      break;

      case 'donaciones':
        obtenerEntradas('donacion')
      break;

      case 'devoluciones':
        obtenerEntradas('devolucion');
      break;

      case 'todas':
        obtenerEntradas('toda');
      break;
    };

  };

  $scope.registrosInsumos = function(datos){
    $scope.busqueda = '';
    $scope.indice = 'Insumos';
    visivility(2);

    switch(datos){

      case 'ordenes':
        obtenerInsumos('orden');
      break;

      case 'donaciones':
        obtenerInsumos('donacion');
      break;

      case 'devoluciones':
        obtenerInsumos('devolucion');
      break;

      case 'todos':
        obtenerInsumos('todo');
      break;
    }
  };

  $scope.detallesOrden = function(orden){

    $http.get('/entradas/getOrden/'+ orden)
      .success(
        function(response){
          $scope.orden   = response.orden;
          $scope.insumos = response.insumos;
          visivility(3);
          $scope.busqueda = '';
          $scope.indice = 'Orden';
      });
  }

  $scope.detallesEntrada = function(index){
    var modalInstance = $modal.open({
      animation: true,
          templateUrl: '/entradas/detalles',
          controller: 'detallesEntradaCtrl',
          windowClass: 'large-Modal',
          resolve: {
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.search = function(){
		visivility(1);

		var modalInstance = $modal.open({
			animation: true,
					templateUrl: '/entradas/search',
					controller: 'serarchEntradaCtrl',
					size: 'lg',
					resolve: {
						 obtenerEntradas:function (){
								return obtenerEntradas;
						 }
					}
		});
	};

  function visivility(menu){
    switch(menu){
      case 1:
        $scope.uiStatus.proformas = true;
        $scope.uiStatus.insumos = false;
        $scope.uiStatus.ordenes = false;
      break;

      case 2:
        $scope.uiStatus.proformas = false;
        $scope.uiStatus.insumos = true;
        $scope.uiStatus.ordenes = false;
      break;

      case 3:
        $scope.uiStatus.proformas = false;
        $scope.uiStatus.insumos = false;
        $scope.uiStatus.ordenes = true;
    }
  }

	obtenerEntradas('toda');

});

angular.module('deposito').controller('detallesEntradaCtrl', function ($scope, $modalInstance, $http, id) {

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

  $scope.detalles = function(){

    $http.get('/entradas/getEntrada/' + id)
      .success(function(response){

        $scope.entrada = response.entrada;
        $scope.insumos = response.insumos;

    });
  };

  $scope.detalles(id);

});

angular.module('deposito').controller('serarchEntradaCtrl', function ($scope, $modalInstance, $http, obtenerEntradas){

	$scope.data = {};
	$scope.data.type  = "all";
	$scope.data.orden = "desc";
  $scope.insumoSelect = {};

	$http.get('getUsuariosDeposito')
		.success(function(response){$scope.usuarios = response});

	$scope.buscar = function(){
		$scope.data.insumo = $scope.insumoSelect.hasOwnProperty('selected') ? $scope.insumoSelect.selected.id : null;

		if( $scope.amountp && $scope.data.cantidadI && $scope.data.cantidadF){
				$scope.data.amountrange = true;
		}

		if($scope.fechaI && $scope.fechaF){
				$scope.data.fechaI = dataForamat($scope.fechaI);
				$scope.data.fechaF = dataForamat($scope.fechaF);
				$scope.data.dateranger = true;
		}

		if($scope.timep){
			$scope.data.hourrange = true;
			$scope.data.horaI = timeFormat($scope.timeI);
			$scope.data.horaF = timeFormat($scope.timeF);
		}

		var config = {
			params:$scope.data
		};

		obtenerEntradas('search',config);
		$modalInstance.dismiss('cancel');
	}

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

	$scope.refreshInsumos = function(insumo) {
		$scope.searchAjax = true;
			var params = {insumo: insumo};
			return $http.get(
				'/getInsumosConsulta',
				{params: params}
			).then(function(response){
				$scope.listInsumos =  response.data
			});
		};

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

		function timeFormat(time){

			var hour = time.getHours();
			var minute = time.getMinutes();

			if( hour < 10 )
				hour = "0" + hour;

			if(minute < 10)
			 	minute = "0" + minute;

			return  hour + '-' + minute;
		}

		$scope.hourR = function(){
			$scope.timep = true;
			$scope.timeI = new Date();
			$scope.timeF = new Date();
		}

		$scope.amountR = function(){
				$scope.amountp = true;
		}

		$scope.dateR = function(){
				$scope.datep = true;
		}

});
