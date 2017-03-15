<div class="modal-header">
	<div class="row">
		<div class="col-md-10">
	    	<h3 class="modal-title ">
	    		<span class="fa fa-arrow-circle-left text-warning"></span> Pro-Forma de salida: <strong class="text-primary"><i class="fa fa-barcode"></i> {#nota.codigo | codeforma#}</strong>
	    	</h3>
	    </div>

	    <div style="text-align:right" class="col-md-2">
			<button class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Filtrar registros" ng-click="chvisibility()"><span class="glyphicon glyphicon-search"></span></button>
				@if(Auth::user()->hasPermissions(['inventory_report']))
			<a class="btn btn-warning" data-toggle="tooltip" data-placement="bottom" title="Generar reporte" href="/reportes/salida/{#nota.id#}" target="_blank">
		        <span class="glyphicon glyphicon glyphicon-print"></span>
		      </a>
				@endif
	    </div>
	</div>
</div>
<div class="modal-body">

	<table class="table table-bordered custon-table-bottom-off" >
		<thead>
			<tr>
				<th class="col-md-1"><i class="glyphicon glyphicon-calendar"></i> Fecha</th>
				<th class="col-md-1"><i class="fa fa-clock-o"></i> Hora</th>
				<th class="col-md-1"><i class="fa fa-object-group"></i> Concepto</th>
				<th class="col-md-1"><i class="glyphicon glyphicon-th"></i> Insumos</th>
				<th class="col-md-3"><i class="glyphicon glyphicon-user"></i> Tercero</th>
				<th class="col-md-3"><i class="fa fa-user"></i> Usuario</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{#nota.fecha#}</td>
				<td>{#nota.hora#}</td>
				<td><span class="text-enlace" tooltip="{#nota.concepto#}">{#nota.abreviatura#}</span></td>
				<td class="text-center">{#insumos.length#}</td>
				<td>{#nota.tercero#}</td>
				<td>{#nota.usuario#}</td>
			</tr>
		</tbody>
	</table>

	<table class="table table-bordered custon-table-top-off">
		<thead>
			<tr>
				<th class="col-md-2"><i class="fa fa-barcode"></i> Código</th>
				<th class="col-md-3"><i class="fa fa-commenting"></i> Descripción</th>
				<th class="col-md-1"><i class="fa fa-cubes"></i> Lote</th>
				<th class="col-md-1"><i class="fa fa-opencart"></i> Solicitado</th>
				<th class="col-md-1"><i class="fa fa-cart-plus"></i> Despachado</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-show="visibility">
				<td>
					<input type="text" class="form-control" placeholder="Código" ng-model="search.codigo">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Descripción" ng-model="search.descripcion">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Número de lote" ng-model="search.lote">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Cantidad" ng-model="search.solicitado">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Cantidad" ng-model="search.despachado">
				</td>
			</tr>
			<tr dir-paginate="insumo in insumos |filter:search:strict| itemsPerPage:5">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.lote#}</td>
				<td class="text-right">{#insumo.solicitado#}</td>
				<td class="text-right">{#insumo.despachado#}</td>
			</tr>
		</tbody>
	</table>

	{{--Paginacion de la tabla de insumos--}}
    <div class="text-center">
 	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
  	</div>

</div>
<div class="modal-footer">
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
</div>
