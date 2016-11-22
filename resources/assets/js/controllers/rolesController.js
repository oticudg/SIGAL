"use strict";


angular.module('deposito').
controller('rolesController',function($scope,$http,$modal){

	$scope.roles = [];
  $scope.cRegistro = '10';

	$scope.obtenerRoles = function(){
		$http.get('/administracion/roles/all')
			.success( function(response){$scope.roles = response});
	};

  $scope.registrarRol = function() {

      $modal.open({
        animation: true,
          templateUrl: '/administracion/roles/registrar',
          windowClass: 'large-Modal',
          controller: 'registrarRolCtrl',
          resolve: {
            obtenerRoles: function (){
              return $scope.obtenerRoles;
            }
          }
      });
  }

  $scope.editarRol = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/administracion/roles/editar',
          windowClass: 'large-Modal',
          controller: 'editarRolCtr',
          resolve: {
             obtenerRoles: function () {
                return $scope.obtenerRoles;
             },
             id:function () {
                return index;
             }
         }
    });
  };

  $scope.eliminarRol = function(index){
    var modalInstance = $modal.open({
      		animation: true,
          templateUrl: '/administracion/roles/eliminar',
          controller: 'eliminarRolCtrl',
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

	$http.get('/administracion/roles/permisos')
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

		$http.post('/administracion/roles/registrar',data)
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

angular.module('deposito').controller('editarRolCtr', function ($scope, $modalInstance, $http, obtenerRoles, id) {

  $scope.btnVisivilidad = true;
	$scope.data = {}
	$scope.data.permisos = [];
	$scope.alert = false;
	var permisos = [];

	$http.get('/administracion/roles/permisos')
		.success(function(response){
			$scope.permisos = response;
		});

  $http.get('/administracion/roles/getRol/' + id)
      .success(function(response){
      	$scope.data.permisos = response.permisos;
				$scope.data.nombre   = response.nombre;
   		}
	);


	$scope.assignPermission = function(permiso){

			var index = $scope.data.permisos.indexOf(permiso);

			if( index != -1){
				$scope.data.permisos.splice(index, 1);
			}
			else{
				$scope.data.permisos.push(permiso);
			}
	}

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(){
    $scope.alert = false;
  };

	$scope.isAsignedPermission = function(permiso){
		return $scope.data.permisos.indexOf(permiso) != -1 ? true:false;
	}

	$scope.registrar = function(){
		$http.post('/administracion/roles/editar/' + id, $scope.data)
	      .success(function(response){
						$scope.alert = {'type':response.status, 'msg':response.message};
						if(response.status == 'success'){
							$scope.btnVisivilidad = false;
							obtenerRoles();
						}
	   		}
		);
	}
});

angular.module('deposito').controller('eliminarRolCtrl', function ($scope, $modalInstance, $http, obtenerRoles,id){

  $scope.btnVisivilidad = true;

  $scope.eliminar = function () {
    $scope.delete();
  };

  $scope.cancelar = function () {
    $modalInstance.dismiss('cancel');
  };

  $scope.closeAlert = function(index){
    $scope.alert = {};
  };

 $scope.delete = function(){

  $http.post('/administracion/roles/eliminar/' + id)
    .success(function(response){
      $scope.alert = {};
      $scope.alert = {"type":response.status , "msg":response.message};

      $scope.btnVisivilidad = ( response.status == "success") ? false : true;
      obtenerRoles();
  });
 };

});
