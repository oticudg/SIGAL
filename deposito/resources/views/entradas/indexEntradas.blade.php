@extends('panel')
@section('bodytag', 'ng-controller="entradasController"')
@section('addscript')
<script src="{{asset('js/entradasController.js')}}"></script>
@endsection

@section('front-page')
	
	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-cog"></span> Administración</li>	
			<li class="nav-active"><span class="glyphicon glyphicon-circle-arrow-down"></span> Entradas</li>
		</ul>
	</nav>
	<br>
	
	<ul class="nav nav-tabs">
		<li class="active" ng-click="registrosEntradas('todas')"><a data-toggle="tab" class="text-enlace" href="#toda">Todas</a></li>
		<li ng-click="registrosEntradas('ordenes')"><a data-toggle="tab" class="text-enlace" href="#orden">Ordenes</a></li>
		<li ng-click="registrosEntradas('donaciones')"><a data-toggle="tab" class="text-enlace" href="#donacion">Donaciones</a></li>
		<li ng-click="registrosEntradas('devoluciones')"><a data-toggle="tab" class="text-enlace" href="#devolucion">Devoluciones</a></li>
	</ul>

	<div class="tab-content">
		
		{{--Panel de registros de todos las entradas--}}
		<div id="toda" class="tab-pane fade in active">
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
					          <li ng-click="registrosEntradas('todas')" ><a href="#">Pro-Formas</a></li>
					          <li ng-click="registrosInsumos('todos')" ><a href="#">Insumos</a></li>
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
			
			{{--Tabla que muestra las pre-formas de todas las entradas--}}
			<div ng-show="uiStatus.proformas">
				<table class="table table-bordered table-hover">
					<thead>
						<caption>Pro-Formas de entradas</caption>
						<tr>
							<th class="col-md-1">Fecha</th>
							<th class="col-md-1">Codigo</th>
							<th class="col-md-1">Tipo</th>
							<th>Proveedor</th>
							<th class="col-md-1">Detalles</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="todos">
							<td>{#entrada.fecha#}</td>
							<td>{#entrada.codigo#}</td>
							<td>{#entrada.type#}</td>
							<td>{#entrada.provedor#}</td>
							<td><button class="btn btn-warning" ng-click="detallesEntrada(entrada.id)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
						</tr>
					</tbody>
				</table>

				{{--Paginacion de la tabla de Pro-Formas--}}	
			    <div class="text-center">
			 	 <dir-pagination-controls boundary-links="true" pagination-id="todos" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
			  	</div>
			</div>

			{{--Tabla que muestra los insumos que han entrado--}}
			<div ng-show="uiStatus.insumos">
				<table class="table table-bordered table-hover">
					<thead>
						<caption>Insumos que han entrado</caption>
						<tr>
							<th class="col-md-1">Fecha</th>
							<th class="col-md-2">Pro-Forma de Entrada</th>
							<th class="col-md-2">Codigo de Insumo</th>
							<th class="col-md-6">Descripción</th>
							<th class="col-md-1">Cantidad</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumosT">
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
			     	 <dir-pagination-controls boundary-links="true" pagination-id="insumosT" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
			    </div>
		  	</div>
		</div>
		
		{{--Panel de registros de ordenes de compra--}}
		<div id="orden" class="tab-pane fade">
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
					          <li ng-click="registrosEntradas('ordenes')" ><a href="#">Pro-Formas</a></li>
					          <li ng-click="registrosInsumos('ordenes')" ><a href="#">Insumos</a></li>
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
			
			{{--Tabla que muestra las pre-formas de entradas por ordenes de compra--}}
			<div ng-show="uiStatus.proformas">
				<table class="table table-bordered table-hover">
					<thead>
						<caption>Pro-Formas por ordenes de compra</caption>
						<tr>
							<th class="col-md-1">Fecha</th>
							<th class="col-md-1">Codigo</th>
							<th class="col-md-1">N° Orden</th>
							<th>Proveedor</th>
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

			{{--Tabla que muestra los insumos que han entrado por ordenes de compra--}}
			<div ng-show="uiStatus.insumos">
				<table class="table table-bordered table-hover">
					<thead>
						<caption>Insumos por ordenes de compra</caption>
						<tr>
							<th class="col-md-1">Fecha</th>
							<th class="col-md-2">Pro-Forma de Entrada</th>
							<th class="col-md-2">Codigo de Insumo</th>
							<th class="col-md-6">Descripción</th>
							<th class="col-md-1">Cantidad</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumosO">
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
			     	 <dir-pagination-controls boundary-links="true" pagination-id="insumosO" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
			    </div>
		  	</div>
			
			{{--Tabla que muestra todos los insumos de una orden de compra--}}
			<div ng-show="uiStatus.ordenes">
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
		</div>

		{{--Panel de registros de donaciones--}}
		<div id="donacion" class="tab-pane fade">
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
					          <li ng-click="registrosEntradas('donaciones')" ><a href="#">Pro-Formas</a></li>
					          <li ng-click="registrosInsumos('donaciones')" ><a href="#">Insumos</a></li>
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
			
			{{--Tabla que muestra las pre-formas de entradas por donacion--}}
			<div ng-show="uiStatus.proformas">
				<table class="table table-bordered table-hover">
					<thead>
						<caption>Pro-Formas por donaciones</caption>
						<tr>
							<th class="col-md-1">Fecha</th>
							<th class="col-md-1">Codigo</th>
							<th>Proveedor</th>
							<th class="col-md-1">Detalles</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="donaciones">
							<td>{#entrada.fecha#}</td>
							<td>{#entrada.codigo#}</td>
							<td>{#entrada.provedor#}</td>
							<td><button class="btn btn-warning" ng-click="detallesEntrada(entrada.id)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
						</tr>
					</tbody>
				</table>

				{{--Paginacion de la tabla de Pro-Formas--}}	
			    <div class="text-center">
			 	 <dir-pagination-controls boundary-links="true" pagination-id="donaciones" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
			  	</div>
			</div>

			{{--Tabla que muestra los insumos que han entrado por donaciones--}}
			<div ng-show="uiStatus.insumos">
				<table class="table table-bordered table-hover">
					<thead>
						<caption>Insumos por donaciones</caption>
						<tr>
							<th class="col-md-1">Fecha</th>
							<th class="col-md-2">Pro-Forma de Entrada</th>
							<th class="col-md-2">Codigo de Insumo</th>
							<th class="col-md-6">Descripción</th>
							<th class="col-md-1">Cantidad</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumosD">
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
			     	 <dir-pagination-controls boundary-links="true" pagination-id="insumosD" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
			    </div>
		  	</div>
		</div>

		{{--Panel de registros de devoluciones--}}
		<div id="devolucion" class="tab-pane fade">
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
					          <li ng-click="registrosEntradas('devoluciones')" ><a href="#">Pro-Formas</a></li>
					          <li ng-click="registrosInsumos('devoluciones')" ><a href="#">Insumos</a></li>
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
			
			{{--Tabla que muestra las pre-formas de entradas por devoluciones--}}
			<div ng-show="uiStatus.proformas">
				<table class="table table-bordered table-hover">
					<thead>
						<caption>Pro-Formas por devoluciones</caption>
						<tr>
							<th class="col-md-1">Fecha</th>
							<th class="col-md-1">Codigo</th>
							<th>Proveedor</th>
							<th class="col-md-1">Detalles</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:cRegistro" pagination-id="donaciones">
							<td>{#entrada.fecha#}</td>
							<td>{#entrada.codigo#}</td>
							<td>{#entrada.provedor#}</td>
							<td><button class="btn btn-warning" ng-click="detallesEntrada(entrada.id)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
						</tr>
					</tbody>
				</table>

				{{--Paginacion de la tabla de Pro-Formas--}}	
			    <div class="text-center">
			 	 <dir-pagination-controls boundary-links="true" pagination-id="donaciones" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
			  	</div>
			</div>

			{{--Tabla que muestra los insumos que han entrado por devoluciones--}}
			<div ng-show="uiStatus.insumos">
				<table class="table table-bordered table-hover">
					<thead>
						<caption>Insumos por devoluciones</caption>
						<tr>
							<th class="col-md-1">Fecha</th>
							<th class="col-md-2">Pro-Forma de Entrada</th>
							<th class="col-md-2">Codigo de Insumo</th>
							<th class="col-md-6">Descripción</th>
							<th class="col-md-1">Cantidad</th>
						</tr>
					</thead>
					<tbody>
						<tr dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro" pagination-id="insumosD">
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
			     	 <dir-pagination-controls boundary-links="true" pagination-id="insumosD" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
			    </div>
		  	</div>
		</div>
	</div>
@endsection
