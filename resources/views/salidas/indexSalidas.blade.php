@extends('base')
@section('bodytag', 'ng-controller="salidasController"')

@section('panel-name', 'Salidas')

@section('content')
	{{--Buscador y Seleccion de Listados de datos--}}

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

							<div class="col-sm-6 text-right">		
								<div class="input-group">
							  		<input type="text" class="form-control" ng-model="busqueda">
							  		<div class="input-group-btn">
								        <button type="button" class="btn btn-primary dropdown-toggle"
								                data-toggle="dropdown">
								         	{#indice#} <span class="caret"></span>
								        </button>

								        <ul class="dropdown-menu pull-right" role="menu">
								          <li ng-click="registrosProformas()" ><a href="#">Pro-Formas</a></li>
								          <li ng-click="registrosInsumos()" ><a href="#">Insumos</a></li>
								        </ul>
									</div>
								</div>
							</div>
						</div>

						<br>

						{{--Tabla que muestra las pre-formas de salidas--}}
						<div ng-show="status">
							<table class="table table-bordered table-hover">
								<thead>
									<caption>Pro-Formas de Pedido</caption>
									<tr>
										<th class="col-md-1">Fecha</th>
										<th class="col-md-1">Codigo</th>
										<th class="col-md-1">Concepto</th>
										<th class="col-md-6">Tercero</th>
										<th class="col-md-1">Detalles</th>
									</tr>
								</thead>
								<tbody>
									<tr dir-paginate="salida in salidas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
										<td>{#salida.fecha#}</td>
										<td>{#salida.codigo | codeforma#}</td>
										<td><span class="text-enlace" tooltip="{#salida.concepto#}">{#salida.abreviatura#}</span></td>
										<td>{#salida.tercero#}</td>
										<td class="text-center"><button class="btn btn-warning" ng-click="detallesSalida(salida.id)"><span class="glyphicon glyphicon glyphicon-eye-open"></span></button></td>
									</tr>
								</tbody>
							</table>

							{{--Paginacion de la tabla de Pro-Formas--}}
						    <div class="text-center">
						 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
						  	</div>
						</div>

						{{--Tabla que muestra los insumos que han salido--}}
						<div ng-hide="status">
							<table class="table table-bordered table-hover">
								<thead>
									<caption>Insumos que han Salido</caption>
									<tr>
										<th class="col-md-1">Fecha</th>
										<th class="col-md-2">Pro-Forma de Pedido</th>
										<th class="col-md-2">Codigo de Insumo</th>
										<th class="col-md-5">Descripción</th>
										<th class="col-md-1">Lote</th>
										<th class="col-md-1 text-right">Solicitado</th>
										<th class="col-md-1 text-right">Despachado</th>
									</tr>
								</thead>
								<tbody>
									<tr dir-paginate="insumo in salidasInsumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumos">
										<td>{#insumo.fecha#}</td>
										<td><span class="text-enlace" ng-click="detallesSalida(insumo.salidaId)">
										{#insumo.salida | codeforma#}</span></td>
										<td>{#insumo.codigo#}</td>
										<td>{#insumo.descripcion#}</td>
										<td>{#insumo.lote#}</td>
										<td class="text-right">{#insumo.solicitado#}</td>
										<td class="text-right">{#insumo.despachado#}</td>
									</tr>
								</tbody>
							</table>

							{{--Paginacion de la tabla de insumos--}}
						    <div class="text-center">
						     	 <dir-pagination-controls boundary-links="true" pagination-id="insumos" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
						    </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection
