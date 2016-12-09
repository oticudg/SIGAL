@extends('base')
@section('bodytag', 'ng-controller="entradasController"')

@section('panel-name', 'Entradas')

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

						{{--Tabla que muestra las pre-formas de entradas--}}
						<div ng-show="status">
							<table class="table table-bordered table-hover">
								<thead>
									<caption>Pro-Formas de entradas</caption>
									<tr>
										<th class="col-md-1">Fecha</th>
										<th class="col-md-1">Codigo</th>
										<th class="col-md-1">Concepto</th>
										<th class="col-md-6">Tercero</th>
										<th class="col-md-1">Detalles</th>
									</tr>
								</thead>
								<tbody>
									<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
										<td>{#entrada.fecha#}</td>
										<td>{#entrada.codigo | codeforma#}</td>
										<td><span class="text-enlace" tooltip="{#entrada.concepto#}">{#entrada.abreviatura#}</span></td>
										<td>{#entrada.tercero#}</td>
										<td class="text-center"><button class="btn btn-warning" ng-click="detallesEntrada(entrada.id)"><span class="glyphicon glyphicon-plus-sign"></span></button></td>
									</tr>
								</tbody>
							</table>

							{{--Paginacion de la tabla de Pro-Formas--}}
						    <div class="text-center">
						 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
						  	</div>
						</div>

						{{--Tabla que muestra los insumos que han entrado--}}
						<div ng-hide="status">
							<table class="table table-bordered table-hover">
								<thead>
									<caption>Insumos que han entrado</caption>
									<tr>
										<th class="col-md-1">Fecha</th>
										<th class="col-md-1">Entrada</th>
										<th class="col-md-2">Codigo de Insumo</th>
										<th class="col-md-6">Descripción</th>
										<th class="col-md-1">Lote</th>
										<th class="col-md-1 text-right">Cantidad</th>
									</tr>
								</thead>
								<tbody>
									<tr dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumos">
										<td>{#insumo.fecha#}</td>
										<td><span ng-click="detallesEntrada(insumo.entradaId)"
										class="text-enlace">{#insumo.entrada | codeforma#}</span></td>
										<td>{#insumo.codigo#}</td>
										<td>{#insumo.descripcion#}</td>
										<td>{#insumo.lote#}</td>
										<td class="text-right">{#insumo.cantidad#}</td>
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
