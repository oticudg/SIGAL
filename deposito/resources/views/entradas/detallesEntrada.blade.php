<div class="modal-header">
    <div class="row">
    	<div class="col-md-6">
		    <h3 class="modal-title text-title-modal">
		    	<span class="glyphicon glyphicon-circle-arrow-down"></span> Pro-Forma de Entrada: <strong>{#entrada.codigo | codeforma#}</strong>
		    </h3>
		  </div>
	    <div class="col-md-4">
					  <h3 class="modal-title text-title-modal" ng-show="entrada.orden">
				    	N° Orden: <strong>{#entrada.orden#}</strong>
				    </h3>
		  </div>
      <div style="text-align:right" class="col-md-2">
        <button class="btn btn-success" ng-click="chvisibility()"><span class="glyphicon glyphicon-search"></span></button>
        @if(Auth::user()->haspermission('inventarioH'))
          <a class="btn btn-warning" href="/reportes/entrada/{#entrada.id#}" target="_blank">
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
				<th>Proveedor</th>
				<th class="col-md-3">Usuario</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{#entrada.fecha#}</td>
				<td>{#entrada.hora#}</td>
				<td>{#entrada.provedor#}</td>
				<td>{#entrada.usuario#}</td>
			</tr>
		</tbody>
	</table>

	<table class="table table-striped custon-table-top-off">
		<thead>
			<tr>
				<th class="col-md-2">Codigo</th>
				<th>Descripción</th>
				<th class="col-md-1">Lote</th>
				<th class="col-md-1">Fecha Vto</th>
				<th class="col-md-2">Cantidad</th>
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
					<input type="text" class="form-control" placeholder="Lote" ng-model="search.lote">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Fecha" ng-model="search.fecha">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Cantidad" ng-model="search.cantidad">
				</td>
			</tr>
			<tr dir-paginate="insumo in insumos |filter:search:strict| itemsPerPage:5">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.lote#}</td>
				<td>{#insumo.fecha#}</td>
				<td>{#insumo.cantidad#}</td>
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
