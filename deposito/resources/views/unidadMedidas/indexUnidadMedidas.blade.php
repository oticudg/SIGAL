@extends('base')
@section('bodytag', 'ng-controller="unidadMedidasController"')
@section('addscript')
<script src="{{asset('js/unidadMedidasController.js')}}"></script>
@endsection

@section('conten')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registrarUnidadMedida()">Nueva Unidad de Medida</button>
	
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
			<tr ng-repeat="unidadMedida in unidadMedidas | filter:busqueda">
				<td>{#unidadMedida.nombre#}</td>
				<td><button class="btn btn-warning" ng-click="editarUnidadMedida(unidadMedida.id)">Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="eliminarPresentacion(presentacion.id)">Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection
