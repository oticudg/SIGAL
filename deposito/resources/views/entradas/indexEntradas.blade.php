@extends('panel')
@section('bodytag', 'ng-controller="entradasController"')
@section('addscript')
<script src="{{asset('js/entradasController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-circle-arrow-down"></span> Entradas
	</h5>
	<br>
	
	{{--Buscador y Seleccion de Listados de datos--}}
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
		  		<div class="input-group-btn">
			        <button type="button" class="btn btn-success dropdown-toggle"
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
	
	{{--Tabla que muestra las pre-formas de entradas--}}
	<div ng-show="proformaVisivility">
		<table class="table table-bordered table-hover">
			<thead>
				<caption>Pro-Formas de Entrada</caption>
				<tr>
					<th class="col-md-1">Fecha</th>
					<th class="col-md-1">Codigo</th>
					<th class="col-md-1">N° Orden</th>
					<th class="col-md-6">Proveedor</th>
					<th class="col-md-1">Detalles</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
					<td>{#entrada.fecha#}</td>
					<td>{#entrada.codigo#}</td>
					<td><span ng-click="detallesOrden(entrada.orden)"
					class="text-enlace">{#entrada.orden#}</span></td>
					<td>{#entrada.provedor#}</td>
					<td><button class="btn btn-warning" ng-click="detallesEntrada(entrada.id)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
				</tr>
			</tbody>
		</table>

		{{--Paginacion de la tabla de Pro-Formas--}}	
	    <div class="text-center">
	 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
	  	</div>
	</div>

	{{--Tabla que muestra los insumos que han entrado--}}
	<div ng-show="insumosVisivility">
		<table class="table table-bordered table-hover">
			<thead>
				<caption>Insumos que han Entrado</caption>
				<tr>
					<th class="col-md-1">Fecha</th>
					<th class="col-md-2">Pro-Forma de Entrada</th>
					<th class="col-md-2">Codigo de Insumo</th>
					<th class="col-md-6">Descripcion</th>
					<th class="col-md-1">Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="insumo in entradasInsumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumos">
					<td>{#insumo.fecha#}</td>
					<td><span ng-click="detallesEntrada(insumo.entradaId)"
					class="text-enlace">{#insumo.entrada#}</span></td>
					<td>{#insumo.codigo#}</td>
					<td>{#insumo.descripcion#}</td>
					<td>{#insumo.cantidad#}</td>
				</tr>
			</tbody>
		</table>
		{{--Paginacion de la tabla de insumos--}}
	    <div class="text-center">
	     	 <dir-pagination-controls boundary-links="true" pagination-id="insumos" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
	    </div>
  	</div>
	
	{{--Tabla que muestra todos los insumos de una orden de compra--}}
	<div ng-show="ordenVisivility">
		<table class="table table-bordered custon-table-bottom-off" >
			<thead>
				<caption>Entradas de esta orden de compra</caption>
				<tr>
					<th class="col-md-2">N° Orden de Compra</th>
					<th>Proveedor</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>{#orden.numero#}</td>
					<td>{#orden.provedor#}</td>
				</tr>
			</tbody>	
		</table>		
		<table class="table table-bordered table-striped custon-table-top-off">
			<thead>
				<tr>
					<th class="col-md-1">Fecha</th>
					<th class="col-md-2">N° Proforma-Entrada</th>
					<th class="col-md-2">Codigo Insumo</th>
					<th class="col-md-6">Descripción</th>
					<th class="col-md-1">Cantidad</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro" 
				pagination-id="ordenInsumos">
					<td>{#insumo.fecha#}</td>
					<td><span ng-click="detallesEntrada(insumo.entradaId)"
					class="text-enlace">{#insumo.entrada#}</span></td>
					<td>{#insumo.codigo#}</td>
					<td>{#insumo.descripcion#}</td>
					<td>{#insumo.cantidad#}</td>
				</tr>
			</tbody>
		</table>

		{{--Paginacion de la tabla de insumos de la orden de compra--}}
	    <div class="text-center">
	     	 <dir-pagination-controls boundary-links="true" pagination-id="ordenInsumos" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
	    </div>
	</div>

@endsection
