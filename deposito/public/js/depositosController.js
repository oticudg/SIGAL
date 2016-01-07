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
