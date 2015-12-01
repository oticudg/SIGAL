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
	@if( Auth::user()->haspermission('usuarioN') )
		<button class="btn btn-success" ng-click="registrarUser()"><span class="glyphicon glyphicon-plus"></span> Nuevo Usuario</button>
	@endif
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
	
	<div class="row">
		<div class="col-md-1">
    		<label for="cantidad">Registros</label>
			<select id="cantidad" class="form-control" ng-model="cRegistro">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>	
			</select>
		</div>
	</div>

	<br>
	<br>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="col-md-3">Nombre</th>
				<th>Usuario</th>
				<th class="col-md-2">Cedula</th>
				@if( Auth::user()->haspermission('usuarioD') && Auth::user()->haspermission('usuarioM'))
					<th class="table-edit" colspan="2">Modificaciones</th>
				@elseif( Auth::user()->haspermission('usuarioD') || Auth::user()->haspermission('usuarioM') )
					<th class="table-edit">Modificaciones</th>
				@endif
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="usuario in usuarios | filter:busqueda | itemsPerPage:cRegistro">
				<td>{#usuario.nombre| capitalize#}</td>
				<td>{#usuario.email#}</td>
				<td>{#usuario.cedula#}</td>
				@if( Auth::user()->haspermission('usuarioM') )
					<td class="table-edit"><button class="btn btn-warning" ng-click="editarUsuario(usuario.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				@endif
				@if( Auth::user()->haspermission('usuarioD'))
					<td class="table-edit"><button class="btn btn-danger"  ng-click="elimUsuario(usuario.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
				@endif
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>
	
@endsection

