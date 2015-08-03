@extends('panel')
@section('bodytag', 'ng-controller="unidadMedidasController"')
@section('addscript')
<script src="{{asset('js/unidadMedidasController.js')}}"></script>
@endsection

@section('front-page')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registrarUnidadMedida()"><span class="glyphicon glyphicon-plus"></span> Nueva Unidad de Medida</button>
	
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
			<tr ng-repeat="unidadMedida in unidadMedidas | filter:busqueda">
				<td>{#unidadMedida.nombre#}</td>
				<td><button class="btn btn-warning" ng-click="editarUnidadMedida(unidadMedida.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="eliminarUnidadMedida(unidadMedida.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection
