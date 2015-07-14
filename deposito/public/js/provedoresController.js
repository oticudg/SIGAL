"use strict";


angular.module('deposito').
controller('provedoresController',function($scope,$http,$modal){

	$scope.provedores = [];

	$scope.registraProvedor = function() {

    	var modalInstance = $modal.open({
     		animation: true,
      		templateUrl: '/modal/provedores/registraProvedor.html',
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

		var modalInstance = $modal.open({

			animation: true,
      		templateUrl: '/modal/provedores/editarProvedor.html',
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

	$scope.obtenerProvedores();

});

angular.module('deposito').controller('registraProvedorCtrl', function ($scope, $modalInstance, $http, obtenerProvedores) {

  $scope.btnVisivilidad = true;
  $scope.rif = '';
  $scope.nombre = '';
  $scope.telefono = '';
  $scope.contacto = '';
  $scope.email = ''; 
  $scope.direccion= '';


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
		'nombre'	:  $scope.nombre,
		'telefono'	:  $scope.telefono,
		'contacto'	:  $scope.contacto,
		'email'		:  $scope.email,
		'direccion' :  $scope.direccion
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
  $scope.telefono = '';
  $scope.contacto = '';
  $scope.email = ''; 
  $scope.direccion= '';

  $http.get('/getProvedor/' + id)
    .success(function(response){

        $scope.rif       = response.rif;
        $scope.nombre    = response.nombre;
        $scope.telefono  = response.telefono;
        $scope.contacto  = response.contacto;
        $scope.email     = response.email;
        $scope.direccion = response.direccion;
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
		'telefono'	:  $scope.telefono,
		'contacto'	:  $scope.contacto,
		'email'		:  $scope.email,
		'direccion' :  $scope.direccion
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