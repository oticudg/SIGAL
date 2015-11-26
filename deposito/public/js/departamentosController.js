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

angular.module('deposito').controller('editarDepartamentoCtrl', function ($scope, $modalInstance, $http, Upload, obtenerDepartamentos,id) {

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
