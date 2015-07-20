@extends('base')
@section('bodytag', 'ng-controller="departamentosController"')
@section('addscript')
<script src="{{asset('js/departamentosController.js')}}"></script>
@endsection

@section('conten')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registrarPresentacion()">Nuevo Departamento</button>
	
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
				<th colspan="2">Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="presentacion in presentaciones | filter:busqueda">
				<td>{#presentacion.nombre#}</td>
				<td><button class="btn btn-warning" ng-click="editarPresentacion(presentacion.id)">Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="eliminarPresentacion(presentacion.id)">Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

