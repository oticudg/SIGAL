"use strict";


angular.module('deposito').
controller('rolesController',function($scope,$http,$modal){

	$scope.roles = [];
  $scope.cRegistro = '5';

	$scope.obtenerRoles = function(){
		$http.get('/roles/all')
			.success( function(response){$scope.roles = response});
	};

  $scope.registrarRol = function() {

      $modal.open({
        animation: true,
          templateUrl: '/roles/registrar',
          windowClass: 'large-Modal',
          controller: 'registrarRolCtrl',
          resolve: {
            obtenerRoles: function (){
              return $scope.obtenerRoles;
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
             obtenerRoles: function (){
                return $scope.obtenerRoles;
             },
             id:function (){
                return index;
             }
         }
    });
  };

	$scope.obtenerRoles();

});

angular.module('deposito').controller('registrarRolCtrl', function ($scope, $modalInstance, $http, obtenerRoles){

	$scope.btnVisivilidad = true;
	$scope.registro = {};
	$scope.permisos = [];
	var permisos = [];

	$http.get('/roles/permisos')
		.success(function(response){
			$scope.permisos = response;
		});


	$scope.assignPermission = function(permiso){

			var index = permisos.indexOf(permiso);

			if( index != -1){
				permisos.splice(index, 1);
			}
			else{
				permisos.push(permiso);
			}
	}

	$scope.registrar = function(){

		var data ={
			'nombre':$scope.nombre,
			'permisos':permisos
		};

		$http.post('/roles/registrar',data)
			.success(function(response){
				$scope.alerts = [];
	      $scope.alerts.push( {"type":response.status , "msg":response.message});

				if( response.status == "success"){
						$scope.btnVisivilidad = false;
			      obtenerRoles();
				}

			});
	}

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alerts.splice(index,1);
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
