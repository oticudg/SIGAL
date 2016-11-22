"use strict";

angular.module('deposito').
controller('insumosController',function($scope,$http,$modal){

	$scope.insumos = [];
  $scope.cRegistro = '10';

	$scope.registrarInsumo = function() {

      $modal.open({
     		animation: true,
      		templateUrl: '/administracion/insumos/registrarInsumo',
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

		$http.get('/administracion/insumos/getInsumos')
			.success( function(response){$scope.insumos = response;$scope.ver=false;});
	};

  $scope.editarInsumo = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/administracion/insumos/editarInsumo',
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
          templateUrl: '/administracion/insumos/eliminarInsumo',
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

    $http.post('/administracion/insumos/registrarInsumo', $data)
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

    $http.get('/administracion/insumos/getInsumo/' + id)
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


  $http.post('/administracion/insumos/editarInsumo/' + id , $data)
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

  $http.post('/administracion/insumos/eliminarInsumo/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
      
      if(response.status == "success")
        obtenerInsumos();
  });
 };

});
