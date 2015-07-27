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

    $http.get('/getUnidades')
      .success( function(response){$scope.unidadMedidas = response});
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
