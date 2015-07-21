@extends('base')
@section('bodytag', 'ng-controller="departamentosController"')
@section('addscript')
<script src="{{asset('js/departamentosController.js')}}"></script>
@endsection

@section('conten')

	<br>
	<br>
	<br>
			
	<a href="/registrarDepartamento"><button class="btn btn-success">Nuevo Departamento</button></a>
	
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
				<td><button class="btn btn-danger" ng-click="eliminarDepartamento(departamento.id)">Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

