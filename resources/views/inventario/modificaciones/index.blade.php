@extends('panel')
@section('bodytag', 'ng-controller="modificacionesController"')

@section('panel-name', 'Modificaciones')

@section('breadcrumb')
	<li><a href="#"><i class="fa fa-dashboard"></i>Exitencias</a></li>
	<li class="active">Salidas</li>
@endsection

@section('content')

	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
					
				</div>	
				<div class="box-body">

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

								<div class="col-md-6 text-right" >
									<button class="btn btn-primary" ng-click="registrarModificacion()" tooltip="Nueva modificacion"><span class="glyphicon glyphicon-plus"></span></button>
									<button class="btn btn-primary" ng-click="search()" tooltip="Filtrar registros">
										<span class="glyphicon glyphicon-search"></span></button>

								</div>
							</div>
						</div>

						<br>

						{{--Tabla que muestra las pre-formas de entradas modificadas--}}
						<div>
							<table class="table table-bordered table-hover">
								<thead>
									<caption>Pro-Formas modificadas</caption>
									<tr>
										<th class="col-md-2">Fecha de modificacion</th>
										<th class="col-md-2">Fecha de registro</th>
										<th class="col-md-2">Pro-Forma</th>
										<th class="col-md-2">Tipo</th>
										<th class="col-md-1">Detalles</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-show="barSearch">
										<td ng-show="status"></td>
										<td>
											<input type="text" class="form-control" placeholder="Codigo" ng-model="busqueda.fechaM">
										</td>
										<td>
											<input type="text" class="form-control" placeholder="Descripción" ng-model="busqueda.fechaR">
										</td>
										<td>
											<input type="text" class="form-control" placeholder="Exist." ng-model="busqueda.codigo">
										</td>
										<td>
											<input type="text" class="form-control" placeholder="Exist." ng-model="busqueda.type">
										</td>
										<td></td>
									</tr>
									<tr dir-paginate="modificacion in modificaciones | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
										<td>{#modificacion.fechaM#}</td>
										<td>{#modificacion.fechaR#}</td>
										<td>{#modificacion.codigo | codeforma#}</td>
										<td>{#modificacion.type#}</td>
										<td class="text-center"><button ng-click="detallesModificacion(modificacion.id)" class="btn btn-warning"><span class="glyphicon glyphicon-plus-sign"></span></button></td>
									</tr>
								</tbody>
							</table>

							{{--Paginacion de la tabla de Pro-Formas--}}
						    <div class="text-center">
						 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
						  	</div>
						</div>

				</div>
			</div>
		</div>

@endsection
