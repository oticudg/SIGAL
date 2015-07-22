@extends('base')
@section('bodytag', 'ng-controller="seccionesController"')
@section('addscript')
<script src="{{asset('js/seccionesController.js')}}"></script>
@endsection

@section('conten')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registrarSeccion()">Nueva Sección</button>
	
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
			<tr ng-repeat="seccion in secciones| filter:busqueda">
				<td>{#seccion.nombre#}</td>
				<td><button class="btn btn-warning" ng-click="editarSeccion(seccion.id)">Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="eliminarSeccion(seccion.id)">Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

