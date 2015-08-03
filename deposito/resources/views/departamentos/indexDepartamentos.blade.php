@extends('panel')
@section('bodytag', 'ng-controller="departamentosController"')
@section('addscript')
<script src="{{asset('js/departamentosController.js')}}"></script>
@endsection

@section('front-page')

	<br>
	<br>
	<br>
			
	<a href="/registrarDepartamento"><button class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Nuevo Departamento</button></a>
	
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
				<th>Division</th>
				<th>Sello</th>
				<th>Firma</th>
				<th>Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="departamento in departamentos | filter:busqueda">
				<td>{#departamento.nombre#}</td>
				<td>{#departamento.division#}</td>
				<td><img src="/files/sellos/{#departamento.sello#}"class="img-thumbnail"  width="304" height="236"></td>
				<td><img src="/files/firmas/{#departamento.firma#}"class="img-thumbnail"  width="304" height="236"></td>
				<td><button class="btn btn-danger" ng-click="eliminarDepartamento(departamento.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

