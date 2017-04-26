<div class="modal-header">
	<div class="row">
    	<div class="col-md-6">
		    <h3 class="modal-title">
		    	<span class="fa fa-cubes text-primary"></span> Lotes
		    </h3>
		</div>
      	<div style="text-align:right" class="col-md-offset-4 col-md-2">
			<button class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Filtrar registros" ng-click="chvisibility()"><span class="glyphicon glyphicon-search"></span></button>
      	</div>
	</div>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-bordered custon-table-bottom-off table-hover">
				<thead>
					<tr>
						<th class="col-md-4"><i class="glyphicon glyphicon-th"></i> Insumo</th>
						<th class="col-sm-1"><i class="fa fa-cubes"></i> Número de lote</th>	
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{#insumo.nombre#}</td>
						<td class="text-right">{#insumo.cantidad#}</td>
					</tr>
				</tbody>
			</table>	
		</div>	
	</div>
	<div class="row">
		<div class="col-sm-12">
			<table class="table table-striped table-bordered custon-table-top-off table-hover">
				<thead>
					<tr>
						<th><i class="fa fa-barcode"></i> Código</th>
						<th><i class="glyphicon glyphicon-calendar"></i> Fecha de vencimiento</th>	
						<th><i class="fa fa-list-ol text-right"></i> Cantidad</th>
					</tr>
				</thead>
				<tbody style="cursor:pointer;">
					<tr ng-show="visibility">
						<td>
							<input type="text" class="form-control" placeholder="Código" ng-model="search.codigo">
						</td>
						<td>
							<input type="text" class="form-control" placeholder="Fecha de vencimiento" ng-model="search.fecha">
						</td>
						<td>
							<input type="text" class="form-control" placeholder="Cantidad" ng-model="search.cantidad">
						</td>
					</tr>
					<tr dir-paginate="lote in lotes | filter:search:strict | itemsPerPage:cRegistro" pagination-id="lotespag" ng-click="select(lote.codigo)">
						<td>{#lote.codigo#}</td>
						<td>{#lote.fecha#}</td>
						<td class="text-right">{#lote.cantidad#}</td>
					</tr>
				</tbody>
			</table>

			{{--Paginacion de la tabla de insumos--}}
		    <div class="text-center">
		 	 <dir-pagination-controls  pagination-id="lotespag" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
		  	</div>

		  	<blockquote ng-hide="lotes.length">No hay lotes registrados.</blockquote>
		</div>
	</div>
</div>
<div class="modal-footer">
	<div class="text-right">
		<button class="btn btn-warning" ng-click="cerrar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
	</div>
</div>