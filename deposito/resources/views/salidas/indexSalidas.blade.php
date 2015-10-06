@extends('panel')
@section('bodytag', 'ng-controller="salidasController"')
@section('addscript')
<script src="{{asset('js/salidasController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-circle-arrow-up"></span> Salidas
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
	
	{{--Tabla que muestra las pre-formas de salidas--}}
	<table ng-show="status" class="table table-bordered table-hover">
		<thead>
			<caption>Pro-Formas de Pedido</caption>
			<tr>
				<th class="col-md-1">Fecha</th>
				<th class="col-md-1">Codigo</th>
				<th class="col-md-6">Servicio</th>
				<th class="col-md-1">Detalles</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="salida in salidas | filter:busqueda:only | itemsPerPage:cRegistro" pagination-id="proformas">
				<td>{#salida.fecha#}</td>
				<td>{#salida.codigo#}</td>
				<td>{#salida.departamento#}</td>
				<td><button class="btn btn-warning" ng-click="detallesSalida(salida.id)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
			</tr>
		</tbody>
	</table>

	{{--Paginacion de la tabla de Pro-Formas--}}	
    <div ng-show="status" class="text-center">
 	 <dir-pagination-controls boundary-links="true" pagination-id="proformas" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
  	</div>

	{{--Tabla que muestra los insumos que han salido--}}
	<table ng-hide="status" class="table table-bordered table-hover">
		<thead>
			<caption>Insumos que han Salido</caption>
			<tr>
				<th class="col-md-1">Fecha</th>
				<th class="col-md-2">Pro-Forma de Pedido</th>
				<th class="col-md-2">Codigo de Insumo</th>
				<th class="col-md-5">Descripcion</th>
				<th class="col-md-1">Solicitado</th>
				<th class="col-md-1">Despachado</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="insumo in salidasInsumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumos">
				<td>{#insumo.fecha#}</td>
				<td ng-click="localizarSalida(insumo.salida)"><span class="text-enlace">{#insumo.salida#}</span></td>
				<td>{#insumo.codigo#}
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.solicitado#}</td>
				<td>{#insumo.despachado#}</td>
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
