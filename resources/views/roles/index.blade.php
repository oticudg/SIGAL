@extends('base')
@section('bodytag', 'ng-controller="rolesController"')

@section('panel-name', 'Roles')

@section('content')

	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					
				</div>	
				<div class="box-body">

					@if( Auth::user()->hasPermissions(['roles_register']) )
						<div class="row">
							<div class="col-md-2">
								<button class="btn btn-primary" ng-click="registrarRol()"><span class="glyphicon glyphicon-plus"></span> Nuevo Rol</button>
							</div>								
						</div>
					@endif

					<br>
					<br>
					<div class="dataTables_wrapper form-inline dt-bootstrap">
						<div class="row">
							<div class="col-sm-6">
								<div class="dataTables_length">
									<span>Mostrar</span>
									<select id="cantidad" class="form-control" ng-model="cRegistro" class="form-control input-sm">
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select> 
								</div>
							</div>

							<div class="col-sm-6 text-right">		
							  	<input type="text" class="form-control" ng-model="busqueda" placeholder="Buscar..">
							</div>
						</div>

						<br>

						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="col-md-10">Nombre</th>
									@if( Auth::user()->hasPermissions(['roles_edit', 'roles_delete'], true))
										<th colspan="2" class="col-sm-1">Modificaciones</th>
									@elseif( Auth::user()->hasPermissions(['roles_edit', 'roles_delete']))
										<th class="col-sm-1">Modificaciones</th>
									@endif
								</tr>
							</thead>
							<tbody>
								<tr dir-paginate="rol in roles | filter:busqueda | itemsPerPage:cRegistro">
									<td>{#rol.nombre | capitalize#}</td>
									@if( Auth::user()->hasPermissions(['roles_edit']))
										<td class="text-center"><button class="btn btn-warning" ng-click="editarRol(rol.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
									@endif
									@if( Auth::user()->hasPermissions(['roles_delete']))
										<td class="text-center"><button class="btn btn-danger" ng-click="eliminarRol(rol.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
									@endif
								</tr>
							</tbody>
						</table>

						<div>
					      <div class="text-center">
					     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
					      </div>
					    </div>	
					</div>
				</div>
			</div>
		</div>
	</div>	

@endsection
