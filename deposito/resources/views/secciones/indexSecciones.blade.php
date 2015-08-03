@extends('panel')
@section('bodytag', 'ng-controller="seccionesController"')
@section('addscript')
<script src="{{asset('js/seccionesController.js')}}"></script>
@endsection

@section('front-page')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registrarSeccion()"><span class="glyphicon glyphicon-plus"></span> Nueva Sección</button>
	
	<br>
	<br>
	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
			</div>
		</div>
	</div>

	<br>
	<br>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Nombre</th>
				<th colspan="2">Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="seccion in secciones| filter:busqueda">
				<td>{#seccion.nombre#}</td>
				<td><button class="btn btn-warning" ng-click="editarSeccion(seccion.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="eliminarSeccion(seccion.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

