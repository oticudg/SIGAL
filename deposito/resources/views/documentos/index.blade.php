@extends('panel')
@section('bodytag', 'ng-controller="documentosController"')
@section('front-page')

	<div data-loading class="div_loader">
		<div id="img_loader" class="img_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>

	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-cog"></span> Administración</li>
			<li class="nav-active"><span class="glyphicon glyphicon-folder-close"></span> Documentos</li>
		</ul>
	</nav>
  <div class="row">
		<div class="col-md-3">
		@if( Auth::user()->hasPermissions(['documents_register']))
			<button class="btn btn-success" ng-click="registrarDocumento()"><span class="glyphicon glyphicon-plus"></span> Nuevo Documento</button>
		@endif
		</div>

		<div class="col-md-9">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-1">
    		<span class="glyphicon glyphicon-filter"></span> Filtrar
			<select id="cantidad" class="form-control" ng-model="cRegistro">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
		</div>
	</div>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="col-md-1">Abreviatura</th>
				<th class="col-md-2">Nombre</th>
				<th class="col-md-1">Tipo</th>
				<th class="col-md-1">Naturaleza</th>
				<th>Uso</th>
				@if( Auth::user()->hasPermissions(['documents_edit', 'documents_delete'], true))
					<th colspan="2" class="table-edit">Modificaciones</th>
				@elseif(  Auth::user()->hasPermissions(['documents_edit', 'documents_delete']))
					<th class="table-edit">Modificaciones</th>
				@endif
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="documento in documentos | filter:busqueda | itemsPerPage:cRegistro">
				<td>{#documento.abreviatura#}</td>
<<<<<<< HEAD
				<td>{#documento.nombre | capitalize#}</td>
				<td>{#documento.tipo | capitalize#}</td>
				<td>{#documento.naturaleza | capitalize #}</td>
				<td>{#documento.uso | capitalize#}</td>
				@if( Auth::user()->hasPermissions(['documents_edit']))
=======
				<td>{#documento.nombre#}</td>
				<td>{#documento.tipo#}</td>
				<td>{#documento.naturaleza #}</td>
				<td>{#documento.uso#}</td>
				@if( Auth::user()->haspermission('departamentoM') )
>>>>>>> fecb599596098da9bfa7d392112199cde044a501
					<td class="table-edit"><button class="btn btn-warning" ng-click="editarDocumento(documento.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				@endif
				@if( Auth::user()->hasPermissions(['documents_delete']))
					<td class="table-edit"><button class="btn btn-danger" ng-click="eliminarDocumento(documento.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
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
