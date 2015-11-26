"use strict";


angular.module('deposito').
controller('usersController',function($scope,$http,$modal){

	$scope.usuarios = [];
  $scope.cRegistro = '5';

	$scope.registrarUser = function() {

    	$modal.open({
     		animation: true,
      		templateUrl: '/registrarUser',
      		size:'lg',
      		controller: 'registraUsuarioCtrl',
      		resolve: {
       			 obtenerUsuarios: function () {
          			return $scope.obtenerUsuarios;
        		 }
      		}
	    });
	}

	$scope.obtenerUsuarios = function(){

		$http.get('/getUsuarios')
			.success( function(response){$scope.usuarios = response;});
	};

	$scope.editarUsuario = function(index){

	  $modal.open({

			animation: true,
      		templateUrl: '/editarUsuario',
      		size:'lg',
      		controller: 'editarUsuarioCtrl',
      		resolve: {
       			 obtenerUsuarios: function () {
          			return $scope.obtenerUsuarios;
        		 },
             id:function () {
                return index;
             }
      	 }
  	});
  };

  $scope.elimUsuario = function(index){

    $modal.open({

      animation: true,
          templateUrl: '/eliminarUsuario',
          controller: 'elimUsuarioCtrl',
          resolve: {
             obtenerUsuarios: function () {
                return $scope.obtenerUsuarios;
             },
             id:function () {
                return index;
             }
         }
    });
  };

	$scope.obtenerUsuarios();

});

angular.module('deposito').controller('registraUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios) {

  $scope.btnVisivilidad = true;
  $scope.data = {
    'pUsuario':false,
    'pUsuarioR':false,
    'pUsuarioM':false,
    'pUsuarioE':false,
    'pProvedor':false,
    'pProvedorR':false,
    'pProvedorM':false,
    'pProvedorE':false,
    'pDepartamento':false,
    'pDepartamentoR':false,
    'pDepartamentoM':false,
    'pDepartamentoE':false,
    'pInsumo':false,
    'pInsumoR':false,
    'pInsumoM':false,
    'pInsumoE':false,
    'pInventario':false,
    'pInventarioH':false,
    'pModificacion':false,
    'pEntrada':false,
    'pEntradaR':false,
    'pEntradaV':false,
    'pSalida':false,
    'pSalidaR':false,
    'pSalidaV':false,
    'pEstadistica':false
  };

  $scope.usuarioActive = function(){
    
    if($scope.data.pUsuario == true){
      $scope.data.pUsuarioR = true;
      $scope.data.pUsuarioM = true;
      $scope.data.pUsuarioE = true;
    }
    else{
      $scope.data.pUsuarioR = false;
      $scope.data.pUsuarioM = false;
      $scope.data.pUsuarioE = false;
    }
  }

  $scope.provedorActive = function(){
    
    if($scope.data.pProvedor == true){
      $scope.data.pProvedorR = true;
      $scope.data.pProvedorM = true;
      $scope.data.pProvedorE = true;
    }
    else{
      $scope.data.pProvedorR = false;
      $scope.data.pProvedorM = false;
      $scope.data.pProvedorE = false;
    }
  }

  $scope.departamentoActive = function(){
    
    if($scope.data.pDepartamento == true){
      $scope.data.pDepartamentoR = true;
      $scope.data.pDepartamentoM = true;
      $scope.data.pDepartamentoE = true;
    }
    else{
      $scope.data.pDepartamentoR = false;
      $scope.data.pDepartamentoM = false;
      $scope.data.pDepartamentoE = false;
    }
  }

  $scope.insumoActive = function(){
    
    if($scope.data.pInsumo == true){
      $scope.data.pInsumoR = true;
      $scope.data.pInsumoM = true;
      $scope.data.pInsumoE = true;
    }
    else{
      $scope.data.pInsumoR = false;
      $scope.data.pInsumoM = false;
      $scope.data.pInsumoE = false;
    }
  }

  $scope.inventarioActive = function(){
    
    if($scope.data.pInventario == true){
      $scope.data.pInventarioH = true;
    }
    else{
      $scope.data.pInventarioH = false;
    }
  }

  $scope.entradaActive = function(){
    
    if($scope.data.pEntrada == true){
      $scope.data.pEntradaV = true;
      $scope.data.pEntradaR = true;
    }
    else{
      $scope.data.pEntradaV = false;
      $scope.data.pEntradaR = false;
    }
  }

  $scope.salidaActive = function(){
    
    if($scope.data.pSalida == true){
      $scope.data.pSalidaV = true;
      $scope.data.pSalidaR = true;
    }
    else{
      $scope.data.pSalidaV = false;
      $scope.data.pSalidaR = false;
    }
  }

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

 	$http.post('/registrarUsuario',$scope.data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerUsuarios();

 	});
 };

});

angular.module('deposito').controller('editarUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios,id) {

  $scope.btnVisivilidad = true;
  $scope.data  = {};
  $http.get('/getUsuario/' + id)
    .success(function(response){

      var usuario = response.usuario;

      usuario.pUsuario  = usuario.pUsuario == 1 ? true : false;
      usuario.pUsuarioR = usuario.pUsuarioR == 1 ? true : false;
      usuario.pUsuarioM = usuario.pUsuarioM == 1 ? true : false;
      usuario.pUsuarioE = usuario.pUsuarioE == 1 ? true : false;
      usuario.pProvedor = usuario.pProvedor == 1 ? true : false;
      usuario.pProvedorR = usuario.pProvedorR == 1 ? true : false;
      usuario.pProvedorM = usuario.pProvedorM == 1 ? true : false;
      usuario.pProvedorE = usuario.pProvedorE == 1 ? true : false;
      usuario.pDepartamento = usuario.pDepartamento == 1 ? true : false;
      usuario.pDepartamentoR = usuario.pDepartamentoR == 1 ? true : false;
      usuario.pDepartamentoM = usuario.pDepartamentoM == 1 ? true : false;
      usuario.pDepartamentoE = usuario.pDepartamentoE == 1 ? true : false;
      usuario.pInsumo = usuario.pInsumo == 1 ? true : false;
      usuario.pInsumoR = usuario.pInsumoR == 1 ? true : false;
      usuario.pInsumoM = usuario.pInsumoM == 1 ? true : false;
      usuario.pInsumoE = usuario.pInsumoE == 1 ? true : false;
      usuario.pInventario = usuario.pInventario == 1 ? true : false;
      usuario.pInventarioH = usuario.pInventarioH == 1 ? true : false;
      usuario.pModificacion = usuario.pModificacion == 1 ? true : false;
      usuario.pEntradaR = usuario.pEntradaR == 1 ? true : false;
      usuario.pEntradaV = usuario.pEntradaV == 1 ? true : false;
      usuario.pEntrada = usuario.pEntradaV  || usuario.pEntradaR  ? true : false;
      usuario.pSalidaR = usuario.pSalidaR == 1 ? true : false;
      usuario.pSalidaV = usuario.pSalidaV == 1 ? true : false;
      usuario.pSalida = usuario.pSalidaV || usuario.pSalidaR  ? true : false;
      usuario.pEstadistica = usuario.pEstadistica == 1 ? true : false;
      
      $scope.data = usuario;
  });

  $scope.usuarioActive = function(){
    
    if($scope.data.pUsuario == true){
      $scope.data.pUsuarioR = true;
      $scope.data.pUsuarioM = true;
      $scope.data.pUsuarioE = true;
    }
    else{
      $scope.data.pUsuarioR = false;
      $scope.data.pUsuarioM = false;
      $scope.data.pUsuarioE = false;
    }
  }

  $scope.provedorActive = function(){
    
    if($scope.data.pProvedor == true){
      $scope.data.pProvedorR = true;
      $scope.data.pProvedorM = true;
      $scope.data.pProvedorE = true;
    }
    else{
      $scope.data.pProvedorR = false;
      $scope.data.pProvedorM = false;
      $scope.data.pProvedorE = false;
    }
  }

  $scope.departamentoActive = function(){
    
    if($scope.data.pDepartamento == true){
      $scope.data.pDepartamentoR = true;
      $scope.data.pDepartamentoM = true;
      $scope.data.pDepartamentoE = true;
    }
    else{
      $scope.data.pDepartamentoR = false;
      $scope.data.pDepartamentoM = false;
      $scope.data.pDepartamentoE = false;
    }
  }

  $scope.insumoActive = function(){
    
    if($scope.data.pInsumo == true){
      $scope.data.pInsumoR = true;
      $scope.data.pInsumoM = true;
      $scope.data.pInsumoE = true;
    }
    else{
      $scope.data.pInsumoR = false;
      $scope.data.pInsumoM = false;
      $scope.data.pInsumoE = false;
    }
  }

  $scope.inventarioActive = function(){
    
    if($scope.data.pInventario == true){
      $scope.data.pInventarioH = true;
    }
    else{
      $scope.data.pInventarioH = false;
    }
  }

  $scope.entradaActive = function(){
    
    if($scope.data.pEntrada == true){
      $scope.data.pEntradaV = true;
      $scope.data.pEntradaR = true;
    }
    else{
      $scope.data.pEntradaV = false;
      $scope.data.pEntradaR = false;
    }
  }

  $scope.salidaActive = function(){
    
    if($scope.data.pSalida == true){
      $scope.data.pSalidaV = true;
      $scope.data.pSalidaR = true;
    }
    else{
      $scope.data.pSalidaV = false;
      $scope.data.pSalidaR = false;
    }
  }

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

 	$http.post('/editarUsuario/' + id ,$scope.data)
 		.success(function(response){

 			$scope.alerts = [];
 			$scope.alerts.push( {"type":response.status , "msg":response.menssage});
 			
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 

      obtenerUsuarios();

 	});
 };

});

angular.module('deposito').controller('elimUsuarioCtrl', function ($scope, $modalInstance, $http, obtenerUsuarios,id) {

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

  $http.post('/eliminarUsuario/' + id)
    .success(function(response){

      $scope.alerts = [];
      $scope.alerts.push( {"type":response.status , "msg":response.menssage});
    
      $scope.btnVisivilidad = ( response.status == "success") ? false : true; 
     
      obtenerUsuarios();
  });

 };

});