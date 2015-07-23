@extends('base')
@section('bodytag', 'ng-controller="insumosController"')
@section('addscript')
<script src="{{asset('js/insumosController.js')}}"></script>
@endsection

@section('conten')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registrarInsumo()">Nuevo Insumo</button>
	<br>
	<br>
	<br>


	<input type="text" class="form-control" ng-model="busqueda">
	<br>
	<br>

	<table class="table">
		<thead>
			<tr>
				<th>Presentacion</th>
				<th>Codigo</th>
				<th>Descripcion</th>
				<th>Deposito</th>
				<th>Ubicacion</th>
				<th colspan="3">Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="insumo in insumos | filter:busqueda">
				<td><img src="files/insumos/{#insumo.imagen#}" class="img-thumbnail"  width="100" height="236"></td>
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.deposito#}</td>
				<td>{#insumo.ubicacion#}</td>
				<td><button class="btn btn-warning" ng-click="editarInsumo(insumo.id)">Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="elimInsumo(insumo.id)">Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

