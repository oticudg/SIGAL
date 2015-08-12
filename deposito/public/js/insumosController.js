"use strict";

angular.module('deposito').
controller('insumosController',function($scope,$http,$modal){

	$scope.insumos = [];

	$scope.registrarInsumo = function() {

    	var modalInstance = $modal.open({
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

		$http.get('/getInsumos')
			.success( function(response){$scope.insumos = response});
	};

  $scope.editarInsumo = function(index){

    var modalInstance = $modal.open({

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

    var modalInstance = $modal.open({

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

angular.module('deposito').controller('registraInsumoCtrl', function ($scope, $modalInstance, $http, Upload, obtenerInsumos) {

  $scope.btnVisivilidad = true;
  $scope.nombre = '';
  $scope.secciones= [];
  $scope.presentaciones = [];
  $scope.unidadMedidas = [];


  $http.get('/getSecciones')
  	.success(function(response){$scope.secciones = response});

  $http.get('/getPresentaciones')
  	.success(function(response){$scope.presentaciones = response});

  $http.get('/getMedidas')
  .success(function(response){$scope.unidadMedidas = response});

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
		'principio_activo'	:  $scope.principio_activo,
		'marca'				:  $scope.marca,
		'presentacion'		:  $scope.presentacion,
		'seccion'			:  $scope.seccion,
		'medida'			:  $scope.medida,
		'cantidadM'			:  $scope.cantidadM,
		'cantidadX'			:  $scope.cantidadX,
		'ubicacion'			:  $scope.ubicacion,
		'deposito'			:  $scope.deposito,
		'descripcion'		:  $scope.descripcion,
 	};


 	Upload.upload({
        url: '/registrarInsumo',
        fields: $data,
        file: $scope.file

    }).success(function(response){

		$scope.alerts = [];
		$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
      obtenerInsumos();

 	});

 };

});

angular.module('deposito').controller('editarInsumoCtrl', function ($scope, $modalInstance, $http, Upload, obtenerInsumos,id) {

  $scope.btnVisivilidad = true;

  $scope.codigo             =   "";    
  $scope.principio_activo   =   "";
  $scope.marca              =   "";
  $scope.presentacion       =   "";
  $scope.seccion            =   "";
  $scope.medida             =   "";
  $scope.cantidadM          =   "";
  $scope.cantidadX          =   "";
  $scope.ubicacion          =   "";
  $scope.deposito           =   "";
  $scope.descripcion        =   "";
  $scope.imagen             =   "";

  $scope.secciones= [];
  $scope.presentaciones = [];
  $scope.unidadMedidas = [];

  $http.get('/getSecciones')
    .success(function(response){$scope.secciones = response});

  $http.get('/getMedidas')
  .success(function(response){$scope.unidadMedidas = response});

  $http.get('/getPresentaciones')
    .success(

      function(response){
        
        $scope.presentaciones = response

        $http.get('/getInsumo/' + id)
          .success(function(response){

          $scope.codigo             =   response.codigo;    
          $scope.principio_activo   =   response.principio_act;
          $scope.marca              =   response.marca;
          $scope.presentacion       =   response.id_presentacion.toString();
          $scope.seccion            =   response.id_seccion.toString();
          $scope.medida             =   response.id_medida.toString();
          $scope.cantidadM          =   response.cant_min;
          $scope.cantidadX          =   response.cant_max;
          $scope.ubicacion          =   response.ubicacion;
          $scope.deposito           =   response.deposito;
          $scope.descripcion        =   response.descripcion;
          $scope.imagen             =   response.imagen;
      });
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
    'principio_activo'  :  $scope.principio_activo,
    'marca'             :  $scope.marca,
    'presentacion'      :  $scope.presentacion,
    'seccion'           :  $scope.seccion,
    'medida'            :  $scope.medida,
    'cantidadM'         :  $scope.cantidadM,
    'cantidadX'         :  $scope.cantidadX,
    'ubicacion'         :  $scope.ubicacion,
    'deposito'          :  $scope.deposito,
    'descripcion'       :  $scope.descripcion
  };


  
  Upload.upload({
        url: '/editarInsumo/' + id,
        fields: $data,
        file: $scope.file

  }).success(function(response){

    $scope.alerts = [];
    $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
    $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
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
     
      obtenerInsumos();
  });
 };

});
