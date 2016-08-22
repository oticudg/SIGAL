@extends('panel')
@section('bodytag', 'ng-controller="modificacionesController"')
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
			<li class="nav-active"><span class="glyphicon glyphicon-edit"></span> Modificaciones</li>
		</ul>
	</nav>
	<br>
	<br>

	<div class="row">
		<div class="col-md-1">
			<label for="cantidad">Registros</label>
			<select id="cantidad" class="form-control" ng-model="cRegistro">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
		</div>

		<div class="col-md-offset-8 col-md-3" style="padding-top:2%; text-align:right;">
			<button class="btn btn-success" ng-click="registrarModificacion()" tooltip="Nueva modificacion"><span class="glyphicon glyphicon-plus"></span></button>
			<button class="btn btn-success" ng-click="search()" tooltip="Filtrar registros">
				<span class="glyphicon glyphicon-search"></span></button>

		</div>
	</div>

	<br>
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
					<td><button ng-click="detallesModificacion(modificacion.id)" class="btn btn-warning"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
				</tr>
			</tbody>
		</table>

		{{--Paginacion de la tabla de Pro-Formas--}}
	    <div class="text-center">
	 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
	  	</div>
	</div>

@endsection
