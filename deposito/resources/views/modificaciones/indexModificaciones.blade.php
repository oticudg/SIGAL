@extends('panel')
@section('bodytag', 'ng-controller="modificacionesController"')
@section('addscript')
<script src="{{asset('js/modificacionesController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-edit"></span> Modificaciones
	</h5>
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
	
	{{--Tabla que muestra las pre-formas de entradas modificadas--}}
	<div>
		<table class="table table-bordered table-hover">
			<thead>
				<caption>Pro-Formas de Entrada Modificadas</caption>
				<tr>
					<th class="col-md-1">Fecha</th>
					<th>Pro-Forma de entrada</th>
					<th class="col-md-1">Detalles</th>
				</tr>
			</thead>
			<tbody>
				<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="proformas">
					<td>{#entrada.fecha#}</td>
					<td>{#entrada.codigo#}</td>
					<td><button ng-click="detallesModificacion(entrada.id)" class="btn btn-warning"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
				</tr>
			</tbody>
		</table>

		{{--Paginacion de la tabla de Pro-Formas--}}	
	    <div class="text-center">
	 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
	  	</div>
	</div>

@endsection