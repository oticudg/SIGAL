"use strict";

angular.module('deposito').
controller('alertController',function($scope,$http){

  $scope.insumoSelect = {};
  $scope.listInsumos = [];
  $scope.insumos = [];

  $scope.refreshInsumos = function(insumo){

    var params = {insumo: insumo};
    return $http.get(
      '/inventario/alertas/getInventarioAlert',
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

  	$http.post('/inventario/alertas/estableceAlarmas', data)
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
});

"use strict";


angular.module('deposito').
controller('departamentosController',function($scope,$http,$modal){

	$scope.departamentos = [];
  $scope.cRegistro = '5';

	$scope.obtenerDepartamentos = function(){

		$http.get('/getDepartamentos')
			.success( function(response){$scope.departamentos = response});
	};

  $scope.registrarDepartamento = function() {

      $modal.open({
        animation: true,
          templateUrl: '/registrarDepartamento',
          size:'lg',
          controller: 'registrarDepartamentoCtrl',
          resolve: {
            obtenerDepartamentos: function () {
              return $scope.obtenerDepartamentos;
            }
          }
      });
  }

  $scope.editarDepartamento = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/editarDepartamento',
          size:'lg',
          controller: 'editarDepartamentoCtrl',
          resolve: {
             obtenerDepartamentos: function () {
                return $scope.obtenerDepartamentos;
             },
             id:function () {
                return index;
             }
         }
    });
  };

  $scope.eliminarDepartamento = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/eliminarDepartamento',
          controller: 'eliminarDepartamentoCtrl',
          resolve: {
             obtenerDepartamentos: function () {
                return $scope.obtenerDepartamentos;
             },
             id:function () {
                return index;
             }
         }
    });
  };
  
	$scope.obtenerDepartamentos();

});

angular.module('deposito').controller('registrarDepartamentoCtrl', function ($scope, $modalInstance, $http, obtenerDepartamentos){

  $scope.btnVisivilidad = true;

  $scope.registrar = function () {
    $scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alerts.splice(index,1);
  };

  $scope.save = function(){

    var $data = {
      'nombre' : $scope.nombre,
    };

    $http.post('/registrarDepartamento', $data)
      .success(function(response){

        $scope.alerts = [];
        $scope.alerts.push( {"type":response.status , "msg":response.menssage});
     
          $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
          obtenerDepartamentos();
    });
  };

});

angular.module('deposito').controller('editarDepartamentoCtrl', function ($scope, $modalInstance, $http, obtenerDepartamentos, id) {

  $scope.btnVisivilidad = true;

  $scope.nombre  =   "";

    $http.get('/getDepartamento/' + id)
        .success(function(response){
        $scope.nombre = response.nombre;    
    });

  $scope.modificar = function () {
    $scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.save = function(){

  var $data = {
    'nombre': $scope.nombre
  };


  $http.post('/editarDepartamento/' + id , $data)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
      
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
      obtenerDepartamentos();
  });

 };
 
});

angular.module('deposito').controller('eliminarDepartamentoCtrl', function ($scope, $modalInstance, $http, obtenerDepartamentos,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/eliminarDepartamento/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerDepartamentos();
  });
 };

});

"use strict";


angular.module('deposito').
controller('depositosController',function($scope,$http,$modal){

	$scope.deposito = [];
  $scope.cRegistro = '5';

	$scope.obtenerDepositos = function(){

		$http.get('/depositos/getDepositos')
			.success( function(response){$scope.depositos = response});
	};

  $scope.registrarDeposito = function() {

      $modal.open({
        animation: true,
          templateUrl: '/depositos/registrarDeposito',
          size:'lg',
          controller: 'registrarDepositoCtrl',
          resolve: {
            obtenerDepositos: function () {
              return $scope.obtenerDepositos;
            }
          }
      });
  }

  $scope.editarDeposito = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/depositos/editarDeposito',
          size:'lg',
          controller: 'editarDepositoCtrl',
          resolve: {
             obtenerDepositos: function () {
                return $scope.obtenerDepositos;
             },
             id:function () {
                return index;
             }
         }
    });
  };

  $scope.eliminarDeposito = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/depositos/eliminarDeposito',
          controller: 'eliminarDepositoCtrl',
          resolve: {
             obtenerDepositos: function () {
                return $scope.obtenerDepositos;
             },
             id:function () {
                return index;
             }
         }
    });
  };
  
	$scope.obtenerDepositos();

});

angular.module('deposito').controller('registrarDepositoCtrl', function ($scope, $modalInstance, $http, obtenerDepositos){

  $scope.btnVisivilidad = true;

  $scope.registrar = function () {
    $scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alerts.splice(index,1);
  };

  $scope.save = function(){

    var $data = {
      'nombre' : $scope.nombre,
    };

    $http.post('/depositos/registrarDeposito', $data)
      .success(function(response){

        $scope.alerts = [];
        $scope.alerts.push( {"type":response.status , "msg":response.menssage});
     
          $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
          obtenerDepositos();
    });
  };

});

angular.module('deposito').controller('editarDepositoCtrl', function ($scope, $modalInstance, $http, obtenerDepositos, id) {

  $scope.btnVisivilidad = true;

  $scope.nombre  =   "";

    $http.get('/depositos/getDeposito/' + id)
        .success(function(response){
        $scope.nombre = response.nombre;
        $scope.codigo = response.codigo;    
    });

  $scope.modificar = function () {
    $scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.save = function(){

  var $data = {
    'nombre': $scope.nombre
  };


  $http.post('/depositos/editarDeposito/' + id , $data)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
      
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
      obtenerDepositos();
  });

 };
});

angular.module('deposito').controller('eliminarDepositoCtrl', function ($scope, $modalInstance, $http, obtenerDepositos,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/depositos/eliminarDeposito/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerDepositos();
  });
 };

});

"use strict";


angular.module('deposito').
controller('documentosController',function($scope,$http,$modal){

	$scope.documentos = [];
  $scope.cRegistro = '5';

	$scope.obtenerDocumentos = function(){
		$http.get('/documentos/all')
			.success( function(response){$scope.documentos = response});
	};

  $scope.registrarDocumento = function() {

      $modal.open({
        animation: true,
          templateUrl: '/documentos/registrar',
          size:'lg',
          controller: 'registrarDocumentoCtrl',
          resolve: {
            obtenerDocumentos: function (){
              return $scope.obtenerDocumentos;
            }
          }
      });
  }

  $scope.editarDocumento = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/documentos/editar',
          size:'lg',
          controller: 'editarDocumentoCtr',
          resolve: {
             obtenerDocumentos: function () {
                return $scope.obtenerDocumentos;
             },
             id:function () {
                return index;
             }
         }
    });
  };

  $scope.eliminarDocumento = function(index){

    var modalInstance = $modal.open({
      		animation: true,
          templateUrl: '/documentos/eliminar',
          controller: 'eliminarDocumentoCtrl',
          resolve: {
             obtenerDocumentos: function (){
                return $scope.obtenerDocumentos;
             },
             id:function (){
                return index;
             }
         }
    });
  };

	$scope.obtenerDocumentos();

});

angular.module('deposito').controller('registrarDocumentoCtrl', function ($scope, $modalInstance, $http, obtenerDocumentos){

	$scope.btnVisivilidad = true;
	$scope.registro = {};
  $scope.registro.tipo = "proveedor";
	$scope.registro.naturaleza = "entrada";

  $scope.registrar = function () {
    $scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alerts.splice(index,1);
  };

  $scope.save = function(){

    $http.post('/documentos/registrar', $scope.registro)
      .success(function(response){
        $scope.alerts = [];
        $scope.alerts.push( {"type":response.status , "msg":response.menssage});
          $scope.btnVisivilidad = ( response.status == "success") ? false : true;
          obtenerDocumentos();
    });
  };

});

angular.module('deposito').controller('editarDocumentoCtr', function ($scope, $modalInstance, $http, obtenerDocumentos, id) {

  $scope.btnVisivilidad = true;

  $http.get('/documentos/get/' + id)
      .success(function(response){
      	$scope.registro = response;
				$scope.registroCopi = {
					'abreviatura' : response.abreviatura,
					'nombre'			: response.nombre,
					'tipo'				: response.tipo,
					'uso'					: response.uso
				};
  		}
	);

  $scope.modificar = function () {
    $scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alerts.splice(index,1);
  };

	var setData = function(){
		var data = {};

		if($scope.registro.abreviatura !=  $scope.registroCopi.abreviatura)
			data.abreviatura = $scope.registro.abreviatura;
		if($scope.registro.nombre !=  $scope.registroCopi.nombre)
			data.nombre = $scope.registro.nombre

		if($scope.registro.tipo != $scope.registroCopi.tipo)
			data.tipo = $scope.registro.tipo;

		if($scope.registro.uso !=  $scope.registroCopi.uso)
			data.uso = $scope.registro.uso;

		return data;
	}

  $scope.save = function(){
	  $http.post('documentos/editar/' + id, setData())
    .success(function(response){
	      $scope.alerts = [];
	      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
	      $scope.btnVisivilidad = ( response.status == "success") ? false : true;
	      obtenerDocumentos();
	  	}
		);
 	};

});

angular.module('deposito').controller('eliminarDocumentoCtrl', function ($scope, $modalInstance, $http, obtenerDocumentos,id){

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alerts.splice(index,1);
  };

 $scope.delet = function(){

  $http.post('/documentos/eliminar/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});

      $scope.btnVisivilidad = ( response.status == "success") ? false : true;
      obtenerDocumentos();
  });
 };

});

"use strict";

angular.module('deposito').
controller('entradasController',function($scope,$http,$modal){

	$scope.entradas = [];
	$scope.insumos = [];
	$scope.indice  = 'Pro-Formas';
  $scope.cRegistro = '5';
  $scope.status = true;

	var obtenerEntradas = function(){
    $http.get('/entradas/getEntradas/')
			.success( function(response){$scope.entradas = response});
  };

  var obtenerInsumos = function(datos){
    $http.get('/entradas/getInsumos')
      .success( function(response){$scope.insumos = response});
  };

	$scope.registrosProformas = function(){
		$scope.busqueda = '';
		$scope.indice = 'Pro-Formas';
		$scope.insumos = [];
		$scope.status = true;
		obtenerEntradas();
	};

	$scope.registrosInsumos = function(){
		$scope.busqueda = '';
		$scope.indice = 'Insumos';
		$scope.insumos = [];
		$scope.status = false;
		obtenerInsumos();
	};

  $scope.detallesOrden = function(orden){

    $http.get('/entradas/getOrden/'+ orden)
      .success(
        function(response){
          $scope.orden   = response.orden;
          $scope.insumos = response.insumos;
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

	obtenerEntradas();
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

"use strict";

angular.module('deposito').
controller('estadisticasController',function($scope,$http){
	
	$scope.formVisivility = true;
	$scope.servicio = null;
	$scope.departamentos = [];
	$scope.listInsumos = [];
	$scope.insumoSelect = {};
	$scope.alert = {}; 
	$scope.dI = null;
	$scope.dF = null;
	$scope.searchAjax = false;

	$http.get('/getDepartamentos')
      .success( function(response){ $scope.departamentos = response;});

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

	$scope.formConsulta = function(){
		$scope.formVisivility = false;
	}

	$scope.formCerrar = function(){
		$scope.formVisivility = true;
	}

	var chartOption = {
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: ''
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> Unidades<br/>'
        },

        series: [{
            name: "Salidas",
            colorByPoint: true,
            data: []
        }],
        drilldown: {
            series: []
        }
    }; 

    function estadisticas(){
	    
	    $http.get('/getEstadisticas')
	    	.success( 
	    		function(response){
					
					chartOption.series[0].data = response.Sdata;
					chartOption.drilldown.series = response.Ddata; 
					chartOption.yAxis.title.text = 'Salidas de insumos';
					chartOption.title.text = response.title;
					chartOption.subtitle.text = 'Haga click en una columna para ver los insumos que han salido por servicio';

					$(function () {
				    	$('#graficaInicial').highcharts(chartOption);
					});
		});
	}

	$scope.closeAlert = function(){
    	$scope.alert = {};
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

	$scope.consultaInsumo = function(){

		$scope.searchAjax = false;

		var data = {

			'fechaI': dataForamat($scope.dI),
			'fechaF': dataForamat($scope.dF),
			'insumo': $scope.insumoSelect.hasOwnProperty('selected') ? $scope.insumoSelect.selected.id : null 
		};

		$http.post('/estadisticasInsumo', data)
			.success(
				function(response){

					if(response.status == 'success'){
						
						chartOption.title.text = response.title;
						chartOption.subtitle.text = '';
					    chartOption.series[0].data = response.data;
					    chartOption.drilldown.series = []; 

						$(function () {
				    		$('#graficaInicial').highcharts(chartOption);
						});

						return;
					}
					
					$scope.alert = {type:response.status , msg: response.menssage};
				}
		);

	}

	$scope.consultaServicio = function(){

		var data = {

			'fechaI': dataForamat($scope.dI),
			'fechaF': dataForamat($scope.dF),
			'servicio': $scope.servicio
		};

		$http.post('/estadisticasServicio', data)
			.success(
				function(response){
					
					if(response.status == 'success'){
						
						chartOption.title.text = response.title;
						chartOption.subtitle.text = '';
					    chartOption.series[0].data = response.data;
					    chartOption.drilldown.series = []; 

						$(function () {
				    		$('#graficaInicial').highcharts(chartOption);
						});

						return;
					}
					
					$scope.alert = {type:response.status , msg: response.menssage};
		    	}
		);

	}


	estadisticas();
});
"use strict";

angular.module('deposito').
controller('insumosAlertController',function($scope,$http,$modal){

	$scope.insumos = [];
	$scope.cRegistro = '5';

	$scope.obtenerInsumos = function(){

		$http.get('/inventario/herramientas/getAlertInsumos')
			.success( function(response){$scope.insumos = response});
	};

	$scope.calculaEstatus = function( min , med , exit){

		if( exit <=  min)
			return "danger";

		if(exit <= med)
			return "warning";
	}

	$scope.obtenerInsumos();

});

"use strict";

angular.module('deposito').
controller('insumosController',function($scope,$http,$modal){

	$scope.insumos = [];
  $scope.cRegistro = '5';

	$scope.registrarInsumo = function() {

      $modal.open({
     		animation: true,
      		templateUrl: '/registrarInsumo',
      		size:'lg',
      		controller: 'registraInsumoCtrl',
      		resolve: {
       			 obtenerInsumos: function () {
          			return $scope.obtenerInsumos;
        		 }
      		}
	    });
	}

	$scope.obtenerInsumos = function(){

    $scope.ver = true;

		$http.get('/getInsumos')
			.success( function(response){$scope.insumos = response;$scope.ver=false;});
	};

  $scope.editarInsumo = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/editarInsumo',
          size:'lg',
          controller: 'editarInsumoCtrl',
          resolve: {
             obtenerInsumos: function () {
                return $scope.obtenerInsumos;
             },
             id:function () {
                return index;
             }
         }
    });
  };

  $scope.elimInsumo = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/eliminarInsumo',
          controller: 'eliminarInsumoCtrl',
          resolve: {
             obtenerInsumos: function () {
                return $scope.obtenerInsumos;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerInsumos();

});

angular.module('deposito').controller('registraInsumoCtrl', function ($scope, $modalInstance, $http, obtenerInsumos){

  $scope.btnVisivilidad = true;

  $scope.registrar = function () {
  	$scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

  $scope.save = function(){

   	var $data = {

  		'codigo'			:  $scope.codigo,
  		'descripcion'		:  $scope.descripcion,
 	  };

    $http.post('/registrarInsumo', $data)
      .success(function(response){

    		$scope.alerts = [];
    		$scope.alerts.push( {"type":response.status , "msg":response.menssage});
        $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

        if(response.status == "success")
          obtenerInsumos();
   	});

 };

});

angular.module('deposito').controller('editarInsumoCtrl', function ($scope, $modalInstance, $http, obtenerInsumos,id) {

  $scope.btnVisivilidad = true;

  $scope.codigo       =   "";    
  $scope.descripcion  =   "";

    $http.get('/getInsumo/' + id)
        .success(function(response){

        $scope.codigo       =   response.codigo;    
        $scope.descripcion  =   response.descripcion;
    });

  $scope.modificar = function () {
    $scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.save = function(){

  var $data = {
    'codigo': $scope.codigo,
    'descripcion': $scope.descripcion
  };


  $http.post('/editarInsumo/' + id , $data)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
      
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
      
      if(response.status == "success")
        obtenerInsumos();
  });

 };
 
});

angular.module('deposito').controller('eliminarInsumoCtrl', function ($scope, $modalInstance, $http, obtenerInsumos,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/eliminarInsumo/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
      
      if(response.status == "success")
        obtenerInsumos();
  });
 };

});

"use strict";

angular.module('deposito').
controller('inventarioController',function($scope,$http,$modal){

	$scope.insumos = [];
	$scope.cRegistro = '5';
	$scope.status = false;
	$scope.all = false;

	var obtenerInsumos = function(data){
		$http.post('/inventario/getInventario', data)
			.success( function(response){
				$scope.insumos = response.insumos;
				$scope.dateI   = response.dateI;
				$scope.dateF   = response.dateF;
				$scope.calculaEstatus($scope.insumos);
			});
	};

	$scope.calculaEstatus = function(insumos){

		for( var insumo in insumos){

			if( insumos[insumo].existencia <= insumos[insumo].min){
				insumos[insumo].color = "danger";
			}
			else if(insumos[insumo].existencia <= insumos[insumo].med){
				insumos[insumo].color = "warning";
			}
			else{
				insumos[insumo].color = "";
			}
		}
	}

	$scope.parcialInventario = function(){
		$scope.status = true;
	}

	$scope.closeSelect = function(){
		$scope.status = false;
		$scope.unselectInsumos();
		$scope.calculaEstatus($scope.insumos);
		$scope.all = false;
	}

	$scope.unselectInsumos = function(){
		for( var insumo in $scope.insumos){
			$scope.insumos[insumo].select = false;
			$scope.insumos[insumo].color  = "";
		}
	}

	$scope.select = function(){

		if($scope.all){
			$scope.selectAll();
		}
		else{
			$scope.unselectInsumos();
		}
	}

	$scope.selectAll = function(){

		if(!$scope.busqueda){
			for( var insumo in $scope.insumos){
				$scope.insumos[insumo].color = "success";
				$scope.insumos[insumo].select = true;
			}
		}
		else{
			for( var insumo in $scope.insumos){

				var descripcion = $scope.insumos[insumo].descripcion.toLowerCase();
				var busqueda = $scope.busqueda.toLowerCase();

				if( descripcion.indexOf(busqueda) == -1 )
					continue;

				$scope.insumos[insumo].color = "success";
				$scope.insumos[insumo].select = true;
			}
		}
	}

	$scope.selectInsumo = function(index){

		if($scope.status == false)
			return;

		if($scope.insumos[index].select){

			$scope.insumos[index].color = "";
			$scope.insumos[index].select = false;
		}
		else{
			$scope.insumos[index].color = "success";
			$scope.insumos[index].select = true;
		}
	};

	$scope.gerenarParcial = function(){

		var data = {
			'insumos':empaquetaData($scope.insumos),
			'date':$scope.dateF
		};

		if($scope.thereIsSelect()){

			$http.post('/reportes/inventario/parcial',data, {responseType:'arraybuffer'})
	  			.success(function (response) {

	       			var file = new Blob([response], {type: 'application/pdf'});
	       			var fileURL = URL.createObjectURL(file);
	       			window.open(fileURL);
			});
  		}
	}

	$scope.thereIsSelect = function(){

		for( var insumo in $scope.insumos){
			if($scope.insumos[insumo].select)
				return true;
		}

		return false;
	}

	$scope.search = function(){
		$scope.busqueda = {};
		$scope.barSearch = $scope.barSearch ? false:true;
	}

	$scope.dateSelect = function(){
		$scope.modalInstance = $modal.open({
			animation: true,
			templateUrl: 'date.html',
			controller:'dateCtrl',
			resolve: {
				 obtenerInsumos:function() {
						return obtenerInsumos;
				 }
		 }
		});
	}

	function empaquetaData(insumos){

		var insumosSelect = [];

		for( var insumo in insumos){

			if( insumos[insumo].select )
				insumosSelect.push(insumos[insumo].id);
		}

		return insumosSelect;
	}

	$scope.current = function(){
		obtenerInsumos();
	}

	$scope.move = function(){
		var data = {
			date:$scope.dateF,
			move:true
		}

		obtenerInsumos(data);
	}

	obtenerInsumos();

});

angular.module('deposito').controller('dateCtrl', function ($scope, $modalInstance, obtenerInsumos) {

	$scope.alert = {};

	$scope.openI = function($event) {
			$event.preventDefault();
			$event.stopPropagation();
			$scope.openedI = true;
	};

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

	$scope.buscar = function(){
		var data ={
			date:dateForamat($scope.fecha)
		}

		if(!$scope.fecha){
			$scope.alert = {type:'danger', msg:'Seleccione fecha a consultar'};
		}
		else if(!rangeDate($scope.fecha)){
			$scope.alert = {type:'danger', msg:'No es posible consultar una fecha superior a la actual'};
		}
		else{
			obtenerInsumos(data);
			$modalInstance.dismiss('cancel');
		}
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

	function rangeDate(date){
		return date.getTime() < (new Date()).getTime();
	}

	$scope.closeAlert = function(){
		$scope.alert = {};
	};

});

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
	$scope.terceroSelect = {};
	$scope.terceros = [];
	$scope.documentos = [];
	$scope.panelTerceros = true;

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

	var getTerceros = function(){
		$http.get('/depositos/terceros')
			.success(function(response){
				$scope.terceros = response;
			});
	}

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
		parseTercero();
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
		$scope.terceroSelect   = {};
		$scope.panelTerceros = true;
		getTerceros();

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

	var parseTercero = function(){
		if($scope.terceroSelect.hasOwnProperty('selected')){
		 	$scope.data.tercero =  $scope.terceroSelect.selected.id;
			$scope.data.tType   =  $scope.terceroSelect.selected.type;
			$scope.data.terceroSearch = true;
		}
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


	$http.get('/documentos/all')
	    .success( function(response){ $scope.documentos = response;});

	getTerceros();

});

"use strict";

angular.module('deposito').
controller('modificacionesController',function($scope,$http,$modal){

	$scope.modificaciones = [];
  $scope.cRegistro = '5';

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
					view:"/entradas/detalles",
					data:"/entradas/getEntrada/",
					id:index
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

"use strict";


angular.module('deposito').
controller('presentacionesController',function($scope,$http,$modal){

	$scope.presentaciones = [];

	$scope.registrarPresentacion = function() {

    	var modalInstance = $modal.open({
     		animation: true,
      		templateUrl: '/registrarPresentacion',
      		size:'lg',
      		controller: 'registraPresentacionCtrl',
      		resolve: {
       			 obtenerPresentaciones: function () {
          			return $scope.obtenerPresentaciones;
        		 }
      		}
	    });
	}

	$scope.obtenerPresentaciones = function(){

		$http.get('/getPresentaciones')
			.success( function(response){$scope.presentaciones = response});
	};


	$scope.editarPresentacion = function(index){

		var modalInstance = $modal.open({

			animation: true,
      		templateUrl: '/editarPresentacion',
      		size:'lg',
      		controller: 'editarPresentacionCtrl',
      		resolve: {
       			 obtenerPresentaciones: function () {
          			return $scope.obtenerPresentaciones;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  $scope.eliminarPresentacion = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/eliminarPresentacion',
          controller: 'eliminarPresentacionCtrl',
          resolve: {
             obtenerPresentaciones: function () {
                return $scope.obtenerPresentaciones;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerPresentaciones();

});

angular.module('deposito').controller('registraPresentacionCtrl', function ($scope, $modalInstance, $http, obtenerPresentaciones) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

  $scope.registrar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {

		'nombre'	:  $scope.nombre,
 	};


 	$http.post('/registrarPresentacion',$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerPresentaciones();

 	});
 };

});


angular.module('deposito').controller('editarPresentacionCtrl', function ($scope, $modalInstance, $http, obtenerPresentaciones,id) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

  $http.get('/getPresentacion/' + id)
    .success(function(response){
        $scope.nombre    = response.nombre;
  });


  $scope.modificar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {

		'nombre'	:  $scope.nombre
 	};


 	$http.post('/editarPresentacion/' + id ,$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerPresentaciones();

 	});
 };

});

angular.module('deposito').controller('eliminarPresentacionCtrl', function ($scope, $modalInstance, $http, obtenerPresentaciones,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/eliminarPresentacion/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerPresentaciones();
  });
 };

});

"use strict";


angular.module('deposito').
controller('provedoresController',function($scope,$http,$modal){

	$scope.provedores = [];
  $scope.cRegistro = '5';

	$scope.registraProvedor = function() {

    	$modal.open({
     		animation: true,
      		templateUrl: '/registraProvedor',
      		size:'lg',
      		controller: 'registraProvedorCtrl',
      		resolve: {
       			 obtenerProvedores: function () {
          			return $scope.obtenerProvedores;
        		 }
      		}
	    });
	}

	$scope.obtenerProvedores = function(){

		$http.get('/getProvedores')
			.success( function(response){$scope.provedores = response});
	};


	$scope.editarProvedor = function(index){

		$modal.open({

			animation: true,
      		templateUrl: '/editarProvedor',
      		size:'lg',
      		controller: 'editarProvedorCtrl',
      		resolve: {
       			 obtenerProvedores: function () {
          			return $scope.obtenerProvedores;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };


  $scope.elimProvedor = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/elimProvedor',
          controller: 'elimProvedorCtrl',
          resolve: {
             obtenerProvedores: function () {
                return $scope.obtenerProvedores;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerProvedores();

});

angular.module('deposito').controller('registraProvedorCtrl', function ($scope, $modalInstance, $http, obtenerProvedores) {

  $scope.btnVisivilidad = true;
  $scope.rif = '';
  $scope.nombre = '';

  $scope.registrar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {
		'rif'		:  $scope.rif,
		'nombre'	:  $scope.nombre
 	};


 	$http.post('/registraProvedor',$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerProvedores();

 	});
 };

});


angular.module('deposito').controller('editarProvedorCtrl', function ($scope, $modalInstance, $http, obtenerProvedores,id) {

  $scope.btnVisivilidad = true;
  $scope.rif = '';
  $scope.nombre = '';
 
  $http.get('/getProvedor/' + id)
    .success(function(response){

        $scope.rif       = response.rif;
        $scope.nombre    = response.nombre;
  });


  $scope.modificar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {
		'nombre'	:  $scope.nombre,
 	};

 	$http.post('/editProvedor/' + id ,$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerProvedores();

 	});
 };

});

angular.module('deposito').controller('elimProvedorCtrl', function ($scope, $modalInstance, $http, obtenerProvedores,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/elimProvedor/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerProvedores();
  });

 };

});
"use strict";

angular.module('deposito').
controller('registroEntradaController',function($scope, $http ,$modal){

  $scope.insumoSelect = {};
  $scope.terceroSelect = {};
  $scope.documentoSelect = {};
  $scope.documentos = [];
  $scope.terceros = [];
  $scope.insumos = [];
  $scope.listInsumos = [];
  $scope.alert = {};


  $scope.openI = function($event, dI) {
      $event.preventDefault();
      $event.stopPropagation();
      $scope.insumos[dI].dI = true;
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

  $http.get('/documentos/all/entradas')
      .success( function(response){ $scope.documentos = response;});

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
        'descripcion':$scope.insumoSelect.selected.descripcion,
        'dI':null
      }
    );

    $scope.insumoSelect = {};
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
      'documento': parseDocumento(),
      'tercero' : parseTercero(),
      'insumos'  : empaquetaData()
    };

    $http.post('/entradas/registrar', data)
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

  $scope.eliminarInsumo = function(index){
    $scope.insumos.splice(index, 1);
  };

  $scope.closeAlert = function(){
    $scope.alert = {};
  };

  $scope.thereInsumos = function(){
    return $scope.insumos.length > 0 ? true:false;
  };

  function insumoExist(codigo, insumos){

    var index;

    for(index in insumos){
      if(insumos[index].codigo  == codigo)
        return true;
    }

    return false;
  };

  function validaCantidad(insumos){
    var index;

    for( index in insumos){
      if( !insumos[index].cantidad || insumos[index].cantidad  < 0 )
        return false;
    }

    return true;
  }

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({
        'id': $scope.insumos[index].id,
        'cantidad':$scope.insumos[index].cantidad,
        'fecha': dataForamat($scope.insumos[index].fecha),
        'lote':$scope.insumos[index].lote
      });
    }

    return insumos;
  }

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

  $scope.restablecer = function(){
    $scope.insumos  = [];
    $scope.alert = {};
    $scope.documentoSelect = {};
    $scope.terceroSelect   = {};
    $scope.panelTerceros = false;
    $scope.insumoSelect = {};
  }

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

});

angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});

"use strict";

angular.module('deposito').
controller('registroSalidaController',function($scope,$http,$modal){

  $scope.insumoSelect = {};
  $scope.terceroSelect = {};
  $scope.documentoSelect = {};
  $scope.terceros = [];
  $scope.documentos = [];
  $scope.listInsumos = [];
  $scope.insumos = [];
  $scope.alert = {};

  $scope.refreshInsumos = function(insumo) {
    var params = {insumo: insumo};
    return $http.get(
      'inventario/getInsumosInventario',
      {params: params}
    ).then(function(response){
      $scope.listInsumos =  response.data
    });
  };

  $http.get('/documentos/all/salidas')
      .success( function(response){ $scope.documentos = response;});

  $scope.agregarInsumos = function(){

    if(!$scope.insumoSelect.selected){
      $scope.alert = {type:"danger" , msg:"Por favor especifique un insumo"};
      return;
    }

    if( insumoExist($scope.insumoSelect.selected.codigo) ){
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

  $scope.registrar = function(){

    if( !validaCantidad() ){
      $scope.alert = {type:"danger" , msg:"Especifique valores validos para cada insumo"};
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

  var save = function($data){

    var $data = {
      'documento': parseDocumento(),
      'tercero' : parseTercero(),
      'insumos'  : empaquetaData()
    };

    $http.post('/registrarSalida', $data)
      .success(
        function(response){

          $scope.loader = false;

          if(response.status == 'unexist'){

            marcaInsumos(response.data);
            $scope.alert = {type:'danger', msg:'La cantidad de los insumos marcados son insuficientes'};
            return;
          }

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

            restablecer();
            return;
          }

          $scope.alert = {type:response.status , msg: response.menssage};
        }
      );
  }

  $scope.eliminarInsumo = function(index){
    $scope.insumos.splice(index, 1);
  };

  $scope.closeAlert = function(){
    $scope.alert = {};
  };

  $scope.thereInsumos = function(){
    return $scope.insumos.length > 0 ? true:false;
  };

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

  function insumoExist(codigo){

    var index;

    for(index in $scope.insumos){
      if($scope.insumos[index].codigo  == codigo)
        return true;
    }

    return false;
  };

  function validaCantidad(){
    var index;

    for( index in $scope.insumos){
      if( !$scope.insumos[index].despachado || $scope.insumos[index].despachado < 0 ||
          $scope.insumos[index].solicitado < $scope.insumos[index].despachado)
        return false;
    }

    return true;
  }

  function empaquetaData(){

    var index;
    var insumos = [];

    for( index in $scope.insumos){
      insumos.push({'id': $scope.insumos[index].id, 'solicitado':$scope.insumos[index].solicitado,
        'despachado':$scope.insumos[index].despachado});
    }

    return insumos;
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

  function restablecer(){
    $scope.insumos  = [];
    $scope.terceros  = [];
    $scope.alert = {};
    $scope.documentoSelect = {};
    $scope.terceroSelect   = {};
    $scope.panelTerceros = false;
    $scope.insumoSelect = {};
  }

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

});

angular.module('deposito').controller('successRegisterCtrl', function ($scope, $modalInstance, response) {

  $scope.response = response;

  $scope.ok = function () {
    $modalInstance.dismiss('cancel');
  };

});

"use strict";


angular.module('deposito').
controller('rolesController',function($scope,$http,$modal){

	$scope.roles = [];
  $scope.cRegistro = '5';

	$scope.obtenerRoles = function(){
		$http.get('/roles/all')
			.success( function(response){$scope.roles = response});
	};

  $scope.registrarRol = function() {

      $modal.open({
        animation: true,
          templateUrl: '/roles/registrar',
          windowClass: 'large-Modal',
          controller: 'registrarRolCtrl',
          resolve: {
            obtenerRoles: function (){
              return $scope.obtenerRoles;
            }
          }
      });
  }

  $scope.editarRol = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/roles/editar',
          windowClass: 'large-Modal',
          controller: 'editarRolCtr',
          resolve: {
             obtenerRoles: function () {
                return $scope.obtenerRoles;
             },
             id:function () {
                return index;
             }
         }
    });
  };

  $scope.eliminarRol = function(index){
    var modalInstance = $modal.open({
      		animation: true,
          templateUrl: '/roles/eliminar',
          controller: 'eliminarRolCtrl',
          resolve: {
             obtenerRoles: function (){
                return $scope.obtenerRoles;
             },
             id:function (){
                return index;
             }
         }
    });
  };

	$scope.obtenerRoles();

});

angular.module('deposito').controller('registrarRolCtrl', function ($scope, $modalInstance, $http, obtenerRoles){

	$scope.btnVisivilidad = true;
	$scope.registro = {};
	$scope.permisos = [];
	var permisos = [];

	$http.get('/roles/permisos')
		.success(function(response){
			$scope.permisos = response;
		});


	$scope.assignPermission = function(permiso){

			var index = permisos.indexOf(permiso);

			if( index != -1){
				permisos.splice(index, 1);
			}
			else{
				permisos.push(permiso);
			}
	}

	$scope.registrar = function(){

		var data ={
			'nombre':$scope.nombre,
			'permisos':permisos
		};

		$http.post('/roles/registrar',data)
			.success(function(response){
				$scope.alerts = [];
	      $scope.alerts.push( {"type":response.status , "msg":response.message});

				if( response.status == "success"){
						$scope.btnVisivilidad = false;
			      obtenerRoles();
				}

			});
	}

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alerts.splice(index,1);
  };

});

angular.module('deposito').controller('editarRolCtr', function ($scope, $modalInstance, $http, obtenerRoles, id) {

  $scope.btnVisivilidad = true;
	$scope.data = {}
	$scope.data.permisos = [];
	$scope.alert = false;
	var permisos = [];

	$http.get('/roles/permisos')
		.success(function(response){
			$scope.permisos = response;
		});

  $http.get('/roles/getRol/' + id)
      .success(function(response){
      	$scope.data.permisos = response.permisos;
				$scope.data.nombre   = response.nombre;
   		}
	);


	$scope.assignPermission = function(permiso){

			var index = $scope.data.permisos.indexOf(permiso);

			if( index != -1){
				$scope.data.permisos.splice(index, 1);
			}
			else{
				$scope.data.permisos.push(permiso);
			}
	}

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(){
    $scope.alert = false;
  };

	$scope.isAsignedPermission = function(permiso){
		return $scope.data.permisos.indexOf(permiso) != -1 ? true:false;
	}

	$scope.registrar = function(){
		$http.post('/roles/editar/' + id, $scope.data)
	      .success(function(response){
						$scope.alert = {'type':response.status, 'msg':response.message};
						if(response.status == 'success'){
							$scope.btnVisivilidad = false;
							obtenerRoles();
						}
	   		}
		);
	}
});

angular.module('deposito').controller('eliminarRolCtrl', function ($scope, $modalInstance, $http, obtenerRoles,id){

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delete();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alert = {};
  };

 $scope.delete = function(){

  $http.post('/roles/eliminar/' + id)
    .success(function(response){
      $scope.alert = {};
      $scope.alert = {"type":response.status , "msg":response.message};

      $scope.btnVisivilidad = ( response.status == "success") ? false : true;
      obtenerRoles();
  });
 };

});

"use strict";

angular.module('deposito').
controller('salidasController',function($scope,$http,$modal){

	$scope.salidas = [];
  $scope.indice = 'Pro-Formas';
  $scope.salidasInsumos = [];
  $scope.cRegistro = '10';
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

"use strict";


angular.module('deposito').
controller('seccionesController',function($scope,$http,$modal){

	$scope.secciones = [];

	$scope.registrarSeccion = function() {

    	var modalInstance = $modal.open({
     		animation: true,
      		templateUrl: '/registrarSeccion',
      		size:'lg',
      		controller: 'registraSeccionCtrl',
      		resolve: {
       			 obtenerSecciones: function () {
          			return $scope.obtenerSecciones;
        		 }
      		}
	    });
	}

	$scope.obtenerSecciones = function(){

		$http.get('/getSecciones')
			.success( function(response){$scope.secciones = response});
	};


	$scope.editarSeccion = function(index){

		var modalInstance = $modal.open({

			animation: true,
      		templateUrl: '/editarSeccion',
      		size:'lg',
      		controller: 'editarSeccionCtrl',
      		resolve: {
       			 obtenerSecciones: function () {
          			return $scope.obtenerSecciones;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  $scope.eliminarSeccion = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/eliminarSeccion',
          controller: 'eliminarSeccionesCtrl',
          resolve: {
             obtenerSecciones: function () {
                return $scope.obtenerSecciones;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerSecciones();

});

angular.module('deposito').controller('registraSeccionCtrl', function ($scope, $modalInstance, $http, obtenerSecciones) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

  $scope.registrar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {

		'nombre'	:  $scope.nombre,
 	};


 	$http.post('/registrarSeccion',$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerSecciones();

 	});
 };

});


angular.module('deposito').controller('editarSeccionCtrl', function ($scope, $modalInstance, $http, obtenerSecciones,id) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

  $http.get('/getSeccion/' + id)
    .success(function(response){
        $scope.nombre    = response.nombre;
  });


  $scope.modificar = function () {
  	$scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

  	$scope.alerts.splice(index,1);

  };


 $scope.save = function(){

 	var $data = {

		'nombre'	:  $scope.nombre
 	};


 	$http.post('/editarSeccion/' + id ,$data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerSecciones();

 	});
 };

});

angular.module('deposito').controller('eliminarSeccionesCtrl', function ($scope, $modalInstance, $http, obtenerSecciones,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/eliminarSeccion/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerSecciones();
  });
 };

});

"use strict";


angular.module('deposito').
controller('unidadMedidasController',function($scope,$http,$modal){

  $scope.unidadMedidas = [];

  $scope.registrarUnidadMedida = function() {

      var modalInstance = $modal.open({
        animation: true,
          templateUrl: '/registrarMedida',
          size:'lg',
          controller: 'registrarMedidaCtrl',
          resolve: {
             obtenerUnidadMedidas: function () {
                return $scope.obtenerUnidadMedidas;
             }
          }
      });
  }

  $scope.obtenerUnidadMedidas = function(){

    $http.get('/getMedidas')
      .success( function(response){$scope.unidadMedidas = response});
  };

  $scope.editarUnidadMedida = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/editarMedida',
          size:'lg',
          controller: 'editarMedidaCtrl',
          resolve: {
             obtenerUnidadMedidas: function () {
                return $scope.obtenerUnidadMedidas;
             },
             id:function () {
                return index;
             }
         }
    });
  };

  $scope.eliminarUnidadMedida = function(index){

    var modalInstance = $modal.open({

      animation: true,
          templateUrl: '/eliminarMedida',
          controller: 'eliminarMedidaCtrl',
          resolve: {
             obtenerUnidadMedidas: function () {
                return $scope.obtenerUnidadMedidas;
             },
             id:function () {
                return index;
             }
         }
    });
  };

  $scope.obtenerUnidadMedidas();

});

angular.module('deposito').controller('registrarMedidaCtrl', function ($scope, $modalInstance, $http, obtenerUnidadMedidas) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

  $scope.registrar = function (){
    $scope.save();
  };


  $scope.cancelar = function (){
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };


 $scope.save = function(){

  var $data = {

    'nombre'  :  $scope.nombre,
  };

  $http.post('/registrarMedida',$data)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
      
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerUnidadMedidas();

  });
 };

});


angular.module('deposito').controller('editarMedidaCtrl', function ($scope, $modalInstance, $http, obtenerUnidadMedidas,id) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';

  $http.get('/getMedida/' + id)
    .success(function(response){
        $scope.nombre    = response.nombre;
  });


  $scope.modificar = function () {
    $scope.save();
  };


  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };


  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };


 $scope.save = function(){

  var $data = {

    'nombre'  :  $scope.nombre
  };


  $http.post('/editarMedida/' + id ,$data)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
      
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerUnidadMedidas();

  });
 };

});

angular.module('deposito').controller('eliminarMedidaCtrl', function ($scope, $modalInstance, $http, obtenerUnidadMedidas,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/eliminarMedida/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerUnidadMedidas();
  });
 };

});

"use strict";


angular.module('deposito').
controller('usersController',function($scope,$http,$modal){

	$scope.usuarios = [];
  $scope.cRegistro = '5';

	$scope.registrarUser = function() {

    	$modal.open({
     		animation: true,
      		templateUrl: '/registrarUser',
      		windowClass: 'large-Modal',
      		controller: 'registraUsuarioCtrl',
      		resolve: {
       			 obtenerUsuarios: function () {
          			return $scope.obtenerUsuarios;
        		 }
      		}
	    });
	}

	$scope.obtenerUsuarios = function(){

		$http.get('/getUsuarios')
			.success( function(response){$scope.usuarios = response;});
	};

	$scope.editarUsuario = function(index){

	  $modal.open({

			animation: true,
      		templateUrl: '/editarUsuario',
      		windowClass: 'large-Modal',
      		controller: 'editarUsuarioCtrl',
      		resolve: {
       			 obtenerUsuarios: function () {
          			return $scope.obtenerUsuarios;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  $scope.elimUsuario = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/eliminarUsuario',
          controller: 'elimUsuarioCtrl',
          resolve: {
             obtenerUsuarios: function () {
                return $scope.obtenerUsuarios;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerUsuarios();

});

angular.module('deposito').controller('registraUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios) {

  $scope.btnVisivilidad = true;
  $scope.depositos = [];
	$scope.roles = [];
	$scope.alert = false;

  $http.get('/depositos/getDepositos')
      .success( function(response){$scope.depositos = response;});

	$http.get('/roles/all')
      .success( function(response){$scope.roles = response;});

  $scope.registrar = function () {
  	$scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
  	$scope.alert = false;
  };

  $scope.save = function(){

 	$http.post('/registrarUsuario',$scope.data)
 		.success(function(response){
 			$scope.alert = {"type":response.status , "msg":response.menssage};
      $scope.btnVisivilidad = ( response.status == "success") ? false : true;
      obtenerUsuarios();
 	});
 };

});

angular.module('deposito').controller('editarUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios,id) {

  $scope.btnVisivilidad = true;
  $scope.data  = {};
  $scope.depositos = [];
	$scope.roles = [];

  $http.get('/depositos/getDepositos')
      .success( function(response){
					$scope.depositos = response;

					$http.get('/getUsuario/' + id)
				    .success(function(response){
				      $scope.data = response.usuario;
							$scope.data.deposito =  String(response.usuario.deposito);
							$scope.data.rol = String(response.usuario.rol);
				  });
	 });

	$http.get('/roles/all')
			.success( function(response){$scope.roles = response;});

  $scope.modificar = function () {
  	$scope.save();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
  	$scope.alert = false;
  };

	$scope.save = function(){
		$http.post('/editarUsuario/' + id ,$scope.data)
			.success(function(response){
				$scope.alert = {"type":response.status , "msg":response.menssage};

	    if( response.status == "success"){
				$scope.btnVisivilidad = false;
		    obtenerUsuarios();
			}

		});
	};

});

angular.module('deposito').controller('elimUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios,id) {

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delet();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){

    $scope.alerts.splice(index,1);

  };

 $scope.delet = function(){

  $http.post('/eliminarUsuario/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});

      $scope.btnVisivilidad = ( response.status == "success") ? false : true;

      obtenerUsuarios();
  });

 };

});

//# sourceMappingURL=deposito.js.map
