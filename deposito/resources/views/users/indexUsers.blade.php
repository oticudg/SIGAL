@extends('panel')
@section('bodytag', 'ng-controller="usersController"')
@section('addscript')
<script src="{{asset('js/usersController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-user"></span> Usuarios
	</h5>

	<br>
			
	<button class="btn btn-success" ng-click="registrarUser()"><span class="glyphicon glyphicon-plus"></span> Nuevo Usuario</button>
	
	<br>
	<br>
	<br>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
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
				<th>Usuario</th>
				<th>Departamento</th>
				<th class="table-edit" colspan="2">Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="usuario in usuarios | filter:busqueda | itemsPerPage:5 ">
				<td>{#usuario.nombre + " " + usuario.apellido#}</td>
				<td>{#usuario.email#}</td>
				<td>{#usuario.rol | uppercase#}</td>
				<td class="table-edit"><button class="btn btn-warning" ng-click="editarUsuario(usuario.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				<td class="table-edit"><button class="btn btn-danger"  ng-click="elimUsuario(usuario.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>
	
@endsection

