<div class="modal-header">
	<div class="row">
		<div class="col-md-10">
	    	<h3 style="color:#54AF54;" class="modal-title">
	    		<span class="glyphicon glyphicon-circle-arrow-up"></span> Pro-Forma de Pedido: <strong>{#salida.codigo | codeforma#}</strong>
	    	</h3>
	    </div>

	    <div class="col-md-2">
	    	<div class="col-md-offset-8 col-md-2">
	    		<button class="btn btn-success" ng-click="chvisibility()"><span class="glyphicon glyphicon-search"></span></button>
	    	</div>
	    </div>
	</div>
</div>
<div class="modal-body">
	
	<table class="table table-bordered custon-table-bottom-off" >
		<thead>
			<tr>
				<th class="col-md-1">Fecha</th>
				<th class="col-md-1">Hora</th>
				<th>Servicio</th>
				<th class="col-md-3">Usuario</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{#salida.fecha#}</td>
				<td>{#salida.hora#}</td>
				<td>{#salida.departamento#}</td>
				<td>{#salida.usuario#}</td>
			</tr>
		</tbody>	
	</table>

	<table class="table table-striped custon-table-top-off">
		<thead>
			<tr>
				<th class="col-md-2">Codigo</th>
				<th>Descripción</th>
				<th class="col-md-1">Solicitado</th>
				<th class="col-md-1">Despachado</th>
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
					<input type="text" class="form-control" placeholder="S" ng-model="search.solicitado">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="D" ng-model="search.despachado">
				</td>
			</tr>
			<tr dir-paginate="insumo in insumos |filter:search:strict| itemsPerPage:5">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.solicitado#}</td>
				<td>{#insumo.despachado#}</td>
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