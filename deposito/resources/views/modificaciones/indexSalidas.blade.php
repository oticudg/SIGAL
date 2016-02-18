@extends('panel')
@section('bodytag', 'ng-controller="modifiSalidasController"')
@section('addscript')
<script src="{{asset('js/modifiSalidasController.js')}}"></script>
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
			<li><span class="glyphicon glyphicon-edit"></span> Modificaciones</li>	
			<li class="nav-active"><span class="glyphicon glyphicon-circle-arrow-up"></span> Salidas</li>
		</ul>
	</nav>
	<br>
	
	<button class="btn btn-success" ng-click="registrarModificacion()"><span class="glyphicon glyphicon-plus"></span> Nuevo Modificación</button>
	<br>
	<br>
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
	
	{{--Tabla que muestra las pre-formas de salidas modificadas--}}
	<div>
		<table class="table table-bordered table-hover">
			<thead>
				<caption>Pro-Formas de salidas modificadas</caption>
				<tr>
					<th class="col-md-1">Fecha</th>
					<th>Pro-Forma de salida</th>
					<th class="col-md-1">Detalles</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="salida in salidas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
					<td>{#salida.fecha#}</td>
					<td>{#salida.codigo | codeforma#}</td>
					<td><button ng-click="detallesModificacion(salida.id)" class="btn btn-warning"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
				</tr>
			</tbody>
		</table>

		{{--Paginacion de la tabla de Pro-Formas--}}	
	    <div class="text-center">
	 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
	  	</div>
	</div>

@endsection