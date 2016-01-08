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