@extends('base')
@section('bodytag', 'ng-controller="entradasController"')

@section('panel-name', '<i class="fa fa-arrow-circle-right text-info"></i> Entradas')

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
							  		<input type="text" class="form-control" placeholder="Buscar..." ng-model="busqueda">
							  		<div class="input-group-btn">
								        <button type="button" class="btn btn-primary dropdown-toggle"
								                data-toggle="dropdown">
								         	{#indice#} <span class="caret"></span>
								        </button>

								        <ul class="dropdown-menu pull-right" role="menu">
								          <li ng-click="registrosProformas()" ><a href="#"><i class="fa fa-file-text-o"></i> Pro-Formas</a></li>
								          <li ng-click="registrosInsumos()" ><a href="#"><i class="glyphicon glyphicon-th"></i> Insumos</a></li>
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
										<th class="col-md-1"><i class="glyphicon glyphicon-calendar"></i> Fecha</th>
										<th class="col-md-1"><i class="fa fa-barcode"></i> Código</th>
										<th class="col-md-1"><i class="fa fa-object-group"></i> Concepto</th>
										<th class="col-md-4"><i class="glyphicon glyphicon-user"></i> Tercero</th>
										<th class="col-md-1"><i class="fa fa-plus-square-o"></i> Detalles</th>
									</tr>
								</thead>
								<tbody>
									<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
										<td>{#entrada.fecha#}</td>
										<td>{#entrada.codigo | codeforma#}</td>
										<td><span class="text-enlace" tooltip="{#entrada.concepto#}">{#entrada.abreviatura#}</span></td>
										<td>{#entrada.tercero#}</td>
										<td class="text-center"><button class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Ver Pro-Forma" ng-click="detallesEntrada(entrada.id)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
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
										<th class="col-md-1"><i class="glyphicon glyphicon-calendar"></i> Fecha</th>
										<th class="col-md-1"><i class="fa fa-arrow-circle-right"></i> Entrada</th>
										<th class="col-md-2"><i class="fa fa-barcode"></i> Código de insumo</th>
										<th class="col-md-5"><i class="fa fa-commenting"></i> Descripción</th>
										<th class="col-md-2"><i class="fa fa-cubes"></i> Lote</th>
										<th class="col-md-1"><i class="fa fa-list-ol"></i> Cantidad</th>
									</tr>
								</thead>
								<tbody>
									<tr dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumos">
										<td>{#insumo.fecha#}</td>
										<td><span ng-click="detallesEntrada(insumo.entradaId)"
										class="text-enlace">{#insumo.entrada | codeforma#}</span></td>
										<td>{#insumo.codigo#}</td>
										<td>{#insumo.descripcion#}</td>
										<td class="text-right">{#insumo.lote#}</td>
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
