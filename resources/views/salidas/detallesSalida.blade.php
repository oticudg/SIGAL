<div class="modal-header">
	<div class="row">
		<div class="col-md-10">
	    	<h3 class="modal-title ">
	    		<span class="glyphicon glyphicon-circle-arrow-up text-primary"></span> Pro-Forma de Pedido: <strong class="text-primary">{#nota.codigo | codeforma#}</strong>
	    	</h3>
	    </div>

	    <div style="text-align:right" class="col-md-2">
	    	<button class="btn btn-primary" ng-click="chvisibility()"><span class="glyphicon glyphicon-search"></span></button>
				@if(Auth::user()->hasPermissions(['inventory_report']))
					<a class="btn btn-warning" href="/reportes/salida/{#nota.id#}" target="_blank">
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
				<th class="col-md-1">Fecha</th>
				<th class="col-md-1">Hora</th>
				<th class="col-md-1">Concepto</th>
				<th class="col-md-1">Insumos</th>
				<th>Tercero</th>
				<th class="col-md-3">Usuario</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{#nota.fecha#}</td>
				<td>{#nota.hora#}</td>
				<td><span class="text-enlace" tooltip="{#nota.concepto#}">{#nota.abreviatura#}</span></td>
				<td>{#insumos.length#}</td>
				<td>{#nota.tercero#}</td>
				<td>{#nota.usuario#}</td>
			</tr>
		</tbody>
	</table>

	<table class="table table-striped custon-table-top-off">
		<thead>
			<tr>
				<th class="col-md-2">Codigo</th>
				<th>Descripción</th>
				<th class="col-md-1">Lote</th>
				<th class="col-md-1 text-right">Solicitado</th>
				<th class="col-md-1 text-right">Despachado</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-show="visibility">
				<td>
					<input type="text" class="form-control" placeholder="Codigo" ng-model="search.codigo">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Descripción" ng-model="search.descripcion">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Descripción" ng-model="search.lote">
				</td>
				<td>
					<input type="text" class="form-control text-right" placeholder="S" ng-model="search.solicitado">
				</td>
				<td>
					<input type="text" class="form-control text-right" placeholder="D" ng-model="search.despachado">
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
