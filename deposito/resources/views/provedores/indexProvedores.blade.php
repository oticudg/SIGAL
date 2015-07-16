@extends('base')
@section('bodytag', 'ng-controller="provedoresController"')
@section('addscript')
<script src="{{asset('js/provedoresController.js')}}"></script>
@endsection

@section('conten')

	<br>
	<br>
	<br>
			
	<button class="btn btn-success" ng-click="registraProvedor()">Nuevo provedor</button>
	<br>
	<br>
	<br>


	<input type="text" class="form-control" ng-model="busqueda">
	<br>
	<br>

	<table class="table">
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
				<td><button class="btn btn-warning" ng-click="editarProvedor(provedor.id)">Editar</button></td>
				<td><button class="btn btn-danger"  ng-click="elimProvedor(provedor.id)">Eliminar</button></td>
			</tr>
		</tbody>

	</table>


@endsection

