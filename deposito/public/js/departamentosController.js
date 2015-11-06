"use strict";


angular.module('deposito').
controller('departamentosController',function($scope,$http,$modal){

	$scope.departamentos = [];
  $scope.cRegistro = '5';

	$scope.obtenerDepartamentos = function(){

		$http.get('/getDepartamentos')
			.success( function(response){$scope.departamentos = response});
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

  $scope.openImagen = function(sello){

    $modal.open({
      animation: true,
      templateUrl: 'imagen.html',
      controller: 'imagenCtrl',
      resolve: {
        sello: function () {
          return sello;
        }
      }
    });
  }

	$scope.obtenerDepartamentos();

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

angular.module('deposito').controller('imagenCtrl', function ($scope, $modalInstance, sello) {

  $scope.btnVisivilidad = true;
  $scope.imagen = sello;

  $scope.cerrar = function () {
    $modalInstance.dismiss('cancel');
  };


});


