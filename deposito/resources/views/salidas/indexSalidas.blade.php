@extends('panel')
@section('bodytag', 'ng-controller="salidasController"')
@section('front-page')

	<div data-loading class="simgle_loader">
		<div id="img_loader" class="img_single_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>

	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-th-list"></span> Inventario</li>
			<li class="nav-active"><span class="glyphicon glyphicon-circle-arrow-up"></span> Salidas</li>
		</ul>
	</nav>
	<br>

	{{--Buscador y Seleccion de Listados de datos--}}
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span ng-click="search()" class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
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
					<td><button class="btn btn-warning" ng-click="detallesSalida(salida.id)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
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
					<th class="col-md-1">Solicitado</th>
					<th class="col-md-1">Despachado</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="insumo in salidasInsumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumos">
					<td>{#insumo.fecha#}</td>
					<td><span class="text-enlace" ng-click="detallesSalida(insumo.salidaId)">
					{#insumo.salida | codeforma#}</span></td>
					<td>{#insumo.codigo#}</td>
					<td>{#insumo.descripcion#}</td>
					<td>{#insumo.solicitado#}</td>
					<td>{#insumo.despachado#}</td>
				</tr>
			</tbody>
		</table>

		{{--Paginacion de la tabla de insumos--}}
	    <div class="text-center">
	     	 <dir-pagination-controls boundary-links="true" pagination-id="insumos" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
	    </div>
	</div>

@endsection
