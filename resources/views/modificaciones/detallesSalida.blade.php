<div class="modal-header">
    <div class="row">
	    <h3 class="modal-title text-title-modal col-md-9">
	    	<span class="glyphicon glyphicon-circle-arrow-down"></span> Pro-Forma de Salida: <strong>{#modificacion.codigo | codeforma#}</strong>
	    </h3>
	</div>
</div>
<div class="modal-body">
	
	<table class="table table-bordered custon-table-bottom-off" >
		<thead>
			<tr>
				<th class="col-md-1">Fecha</th>
				<th class="col-md-1">Hora</th>
				<th>Usuario Modificador</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{#modificacion.fecha#}</td>
				<td>{#modificacion.hora#}</td>
				<td>{#modificacion.usuario#}</td>
			</tr>
		</tbody>	
	</table>

	<table class="table table-bordered custon-table-bottom-off custon-table-top-off" >
		<thead>
			<tr>
				<th ng-hide="salida.Mdepartamento">Servicio</th>
				<th ng-show="salida.Mdepartamento" colspan="2">Servicio <span class="glyphicon glyphicon-resize-horizontal"></span> Modificado</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{#salida.departamento#}</td>
				<td ng-show="salida.Mdepartamento" class="col-md-6 warning">{#salida.Mdepartamento#}</td>
			</tr>
		</tbody>	
	</table>
		
	<table ng-show="insumos"class="table table-striped custon-table-top-off">
		<thead>
			<tr>
				<th class="col-md-2">Codigo</th>
				<th class="col-md-4">Descripción</th>
				<th class="col-md-3" colspan="2">Solicitado <span class="glyphicon glyphicon-resize-horizontal"></span> Modificado</th>
				<th class="col-md-3 separ" colspan="2">Despachado <span class="glyphicon glyphicon-resize-horizontal"></span> Modificado</th>
			</tr>
		</thead>
		<tbody>
			<tr  dir-paginate="insumo in insumos | itemsPerPage:5">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td class="col-md-1">{#insumo.Osolicitado#}</td>
				<td class="col-md-1 warning" ng-show="insumo.Msolicitado">{#insumo.Msolicitado#}</td>
				<td class="col-md-1 default" ng-hide="insumo.Msolicitado">Sin cambio</td>
				<td class="col-md-1 separ">{#insumo.Odespachado#}</td>
				<td class="col-md-1 warning">{#insumo.Mdespachado#}</td>
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