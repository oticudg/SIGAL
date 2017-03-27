@extends('base')
@section('bodytag', 'ng-controller="depositosController"')

@section('panel-name', '<i class="glyphicon glyphicon-inbox text-info"></i> Almacenes')

@section('content')
	
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					
				</div>	
				<div class="box-body">

					@if(Auth::user()->hasPermissions(['stores_register']))
						<div class="row">
							<div class="col-md-2">
								<button class="btn btn-primary" ng-click="registrarDeposito()"><span class="glyphicon glyphicon-plus"></span> Nuevo almacén</button>
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
							  	<input type="text" class="form-control" ng-model="busqueda" placeholder="Buscar...">
							</div>
						</div>

						<br>

						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="col-md-2"><i class="fa fa-barcode" aria-hidden="true"></i> Código</th>
									<th class="col-md-2"><i class="glyphicon glyphicon-inbox"></i> Nombre</th>
									@if( Auth::user()->hasPermissions(['stores_edit', 'stores_delete'], true))
									<th colspan="2" class="col-md-1"><i class="glyphicon glyphicon-edit"></i> Modificar</th>
									@elseif( Auth::user()->hasPermissions(['stores_edit', 'stores_delete']))
										<th class="col-sm-1"><i class="glyphicon glyphicon-edit"></i> Modificar</th>
									@endif
								</tr>
							</thead>
							<tbody>
								<tr dir-paginate="deposito in depositos | filter:busqueda | itemsPerPage:cRegistro">
									<td>{#deposito.codigo#}</td>
									<td>{#deposito.nombre | capitalize#}</td>
									@if( Auth::user()->hasPermissions(['stores_edit']) )
										<td class="text-center"><button class="btn btn-warning" ng-click="editarDeposito(deposito.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
									@endif
									@if( Auth::user()->hasPermissions(['stores_delete']) )
										<td class="text-center"><button class="btn btn-danger" ng-click="eliminarDeposito(deposito.id)"><span class="glyphicon glyphicon-trash"></span> Eliminar</button></td>
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
