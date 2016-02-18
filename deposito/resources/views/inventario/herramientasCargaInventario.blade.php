@extends('panel')
@section('bodytag', 'ng-controller="listCargasController"')
@section('addscript')
<script src="{{asset('js/herramientasInventarioController.js')}}"></script>
@endsection

@section('front-page')
	
	<div data-loading class="div_loader">
		<div id="img_loader" class="img_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>
	
	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-th-list"></span> Inventario</li>
			<li><a href="{{route('invenHerraInicio')}}"><span class="glyphicon glyphicon-wrench"></span> Herramientas</a></li>	
			<li class="nav-active"><span class="glyphicon glyphicon-circle-arrow-down"></span> Cargas de inventario</li>
		</ul>
	</nav>
	<br>
	
	{{--Buscador y Seleccion de Listados de datos--}}
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
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
	
	{{--Tabla que muestra las pre-formas de entradas por ordenes de compra--}}
	<div>
		<table class="table table-bordered table-hover">
			<thead>
				<caption>Cargas de inventario</caption>
				<tr>
					<th class="col-md-1">Fecha</th>
					<th>Codigo de carga de inventario</th>
					<th class="col-md-1">Detalles</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
					<td>{#entrada.fecha#}</td>
					<td>{#entrada.codigo | codeforma#}</td>
					<td><button class="btn btn-warning" ng-click="detallesCarga(entrada.id)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
				</tr>
			</tbody>
		</table>

		{{--Paginacion de la tabla de Pro-Formas--}}	
	    <div class="text-center">
	 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
	  	</div>
	</div>

@endsection
