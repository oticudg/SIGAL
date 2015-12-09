<div class="modal-header">
    <div class="row">
	    <h3 class="modal-title text-title-modal col-md-9">
	    	<span class="glyphicon glyphicon-circle-arrow-down"></span> Pro-Forma de Entrada: <strong>{#entrada.codigo#}</strong>
	    </h3>
		<h3 class="modal-title text-title-modal">
	    	N° Orden: <strong>{#entrada.orden#}</strong>
	    </h3>
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
				<th class="col-md-2">Cantidad</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="insumo in insumos | itemsPerPage:5">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
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