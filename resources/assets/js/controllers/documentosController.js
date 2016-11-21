"use strict";


angular.module('deposito').
controller('documentosController',function($scope,$http,$modal){

	$scope.documentos = [];
  $scope.cRegistro = '10';

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
