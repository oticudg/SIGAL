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
			         	Registros <span class="caret"></span>
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
	<br>
	
	{{--Tabla que muestra las pre-formas de entradas--}}
	<table ng-show="status" class="table table-bordered table-hover">
		<thead>
			<caption>Pro-Formas de Entrada</caption>
			<tr>
				<th class="col-md-1">Fecha</th>
				<th class="col-md-1">Codigo</th>
				<th class="col-md-6">Proveedor</th>
				<th class="col-md-1">Detalles</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:1" pagination-id="proformas">
				<td>{#entrada.fecha#}</td>
				<td>{#entrada.codigo#}</td>
				<td>{#entrada.provedor#}</td>
				<td><button class="btn btn-warning" ng-click="detallesEntrada(entrada.id)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
			</tr>
		</tbody>
	</table>

	{{--Paginacion de la tabla de Pro-Formas--}}	
    <div ng-show="status" class="text-center">
 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
  	</div>


	{{--Tabla que muestra los insumos que han entrado--}}
	<table ng-hide="status" class="table table-bordered table-hover">
		<thead>
			<caption>Insumos que han Entrado</caption>
			<tr>
				<th class="col-md-2">Fecha</th>
				<th class="col-md-2">Pro-Forma de Entrada</th>
				<th class="col-md-2">Codigo de Insumo</th>
				<th class="col-md-3">Descripcion</th>
				<th class="col-md-2">Cantidad</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="insumo in entradasInsumos | filter:busqueda | itemsPerPage:1" pagination-id="insumos">
				<td>{#insumo.fecha#}</td>
				<td ng-click="localizarEntrada(insumo.entrada)"><span class="text-enlace">{#insumo.entrada#}</span></td>
				<td>{#insumo.codigo#}
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.cantidad#}</td>
			</tr>
		</tbody>
		<tfoot>
			
		</tfoot>
	</table>

	{{--Paginacion de la tabla de insumos--}}
    <div ng-hide="status" class="text-center">
     	 <dir-pagination-controls boundary-links="true" pagination-id="insumos" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
    </div>
  
@endsection
