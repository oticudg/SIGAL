@extends('base')
@section('bodytag', 'ng-controller="usersController"')
@section('addscript')
<script src="{{asset('js/usersController.js')}}"></script>
@endsection

@section('conten')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registrarUser()">Nuevo Usuario</button>
	
	<br>
	<br>
	<br>
	
	<input type="text" class="form-control" ng-model="busqueda">
	<br>
	<br>

	<table class="table">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Usuario</th>
				<th>Departamento</th>
				<th colspan="2">Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="usuario in usuarios| filter:busqueda">
				<td>{#usuario.nombre + " " + usuario.apellido#}</td>
				<td>{#usuario.email#}</td>
				<td>{#usuario.rol | uppercase#}</td>
				<td><button class="btn btn-warning" ng-click="editarUsuario(usuario.id)">Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="elimUsuario(usuario.id)">Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

