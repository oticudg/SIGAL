"use strict";


angular.module('deposito').
controller('depositosController',function($scope,$http,$modal){

	$scope.deposito = [];
  $scope.cRegistro = '10';

	$scope.obtenerDepositos = function(){

		$http.get('/administracion/almacenes/getDepositos')
			.success( function(response){$scope.depositos = response});
	};

  $scope.registrarDeposito = function() {

      $modal.open({
        animation: true,
          templateUrl: '/administracion/almacenes/registrarDeposito',
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
          templateUrl: '/administracion/almacenes/editarDeposito',
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
          templateUrl: '/administracion/almacenes/eliminarDeposito',
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

    $http.post('/administracion/almacenes/registrarDeposito', $data)
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

    $http.get('/administracion/almacenes/getDeposito/' + id)
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


  $http.post('/administracion/almacenes/editarDeposito/' + id , $data)
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

  $http.post('/administracion/almacenes/eliminarDeposito/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerDepositos();
  });
 };

});
