@extends('base')
@section('bodytag', 'ng-controller="modificacionesController"')

@section('panel-name', '<i class="glyphicon glyphicon-edit text-info"></i> Modificaciones')

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
									<button class="btn btn-primary" ng-click="registrarModificacion()" tooltip="Nueva modificación"><span class="glyphicon glyphicon-plus"></span></button>
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
										<th class="col-md-2"><i class="glyphicon glyphicon-calendar"></i> Fecha de modificación</th>
										<th class="col-md-2"><i class="glyphicon glyphicon-calendar"></i> Fecha de registro</th>
										<th class="col-md-2"><i class="fa fa-file-text"></i> Pro-Forma</th>
										<th class="col-md-2"><i class="fa fa-cube"></i> Tipo</th>
										<th class="col-md-1"><i class="fa fa-plus-square-o"></i> Detalles</th>
									</tr>
								</thead>
								<tbody>
									<tr ng-show="barSearch">
										<td ng-show="status"></td>
										<td>
											<input type="text" class="form-control" placeholder="Fecha" ng-model="busqueda.fechaM">
										</td>
										<td>
											<input type="text" class="form-control" placeholder="Fecha" ng-model="busqueda.fechaR">
										</td>
										<td>
											<input type="text" class="form-control" placeholder="Código" ng-model="busqueda.codigo">
										</td>
										<td>
											<input type="text" class="form-control" placeholder="Naturaleza" ng-model="busqueda.type">
										</td>
										<td></td>
									</tr>
									<tr dir-paginate="modificacion in modificaciones | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
										<td>{#modificacion.fechaM#}</td>
										<td>{#modificacion.fechaR#}</td>
										<td>{#modificacion.codigo | codeforma#}</td>
										<td>{#modificacion.type#}</td>
										<td class="text-center"><button ng-click="detallesModificacion(modificacion.id)" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Ver  modificación"><span class="glyphicon glyphicon-plus-sign"></span></button></td>
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
