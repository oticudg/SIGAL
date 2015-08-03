@extends('panel')
@section('bodytag', 'ng-controller="provedoresController"')
@section('addscript')
<script src="{{asset('js/provedoresController.js')}}"></script>
@endsection

@section('front-page')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registraProvedor()"><span class="glyphicon glyphicon-plus"></span> Nuevo provedor</button>
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
				<th>Rif</th>
				<th>Nombre</th>
				<th>Telefono</th>
				<th>Contacto</th>
				<th>Direccion</th>
				<th>Gmail</th>
				<th colspan="2">Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="provedor in provedores | filter:busqueda">
				<td>{#provedor.rif#}</td>
				<td>{#provedor.nombre#}</td>
				<td>{#provedor.telefono#}</td>
				<td>{#provedor.contacto#}</td>
				<td>{#provedor.direccion#}</td>
				<td>{#provedor.email#}</td>
				<td><button class="btn btn-warning" ng-click="editarProvedor(provedor.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="elimProvedor(provedor.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

