@extends('panel')
@section('bodytag', 'ng-controller="insumosController"')
@section('addscript')
<script src="{{asset('js/insumosController.js')}}"></script>
@endsection

@section('front-page')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registrarInsumo()"><span class="glyphicon glyphicon-plus"></span> Nuevo Insumo</button>
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
				<td><button class="btn btn-warning" ng-click="editarInsumo(insumo.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="elimInsumo(insumo.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

