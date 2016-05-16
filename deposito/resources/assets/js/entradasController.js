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
		$scope.entradas = [];
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
		$scope.insumos = [];
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
					windowClass:'large-Modal',
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

        $scope.nota = response.nota;
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
	$scope.userSelect   = {};
	$scope.proveSelect  = {};

	$http.get('getUsuariosDeposito')
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
		parseInsumo();
		parseAmount();
		parseUser();
		parseProve();
		parseDate();
		parseTime();

		var config = {
			params:$scope.data
		};

		obtenerEntradas('search',config);
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

	$scope.provedorType = function(type){

		if(type != 'all'){

			$scope.provedorp = true;

			if(type == 'devolucion'){

				$scope.proveSelect  = {};
				$http.get('/getDepartamentos')
			      .success( function(response){ $scope.provedores = response;});
			}
			else{

				$scope.proveSelect  = {};
				$http.get('/getProvedores')
			    .success( function(response){ $scope.provedores = response;});
			}
		}
		else{
			$scope.provedorp = false;
		}
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

	var parseProve = function(){
		if($scope.proveSelect.hasOwnProperty('selected'))
			$scope.data.prove = $scope.proveSelect.selected.id;
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
