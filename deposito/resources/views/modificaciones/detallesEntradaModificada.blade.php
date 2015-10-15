<div class="modal-header">
    <div class="row">
	    <h3 class="modal-title text-title-modal col-md-9">
	    	<span class="glyphicon glyphicon-circle-arrow-down"></span> Pro-Forma de Entrada: <strong>{#modificacion.codigo#}</strong>
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
				<th ng-hide="entrada.Morden" class="col-md-4">N° Orden</th>
				<th ng-show="entrada.Morden" class="col-md-4">N° Orden <span class="glyphicon glyphicon-resize-horizontal"></span> Modificada</th>
				<th ng-hide="entrada.Mprovedor"class="col-md-8">Proveedor</th>
				<th ng-show="entrada.Mprovedor" class="col-md-8" colspan="2">Proveedor <span class="glyphicon glyphicon-resize-horizontal"></span> Modificado</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="col-md-4" ng-hide="entrada.Morden">{#entrada.orden#}</td>
				<td class="col-md-2 warning" ng-show="entrada.Morden">
				{#entrada.orden#} <span class="glyphicon glyphicon-resize-horizontal"></span> {#entrada.Morden#}</td>
				<td class="col-md-8" ng-hide="entrada.Mprovedor">{#entrada.provedor#}</td>
				<td class="col-md-4 warning" ng-show="entrada.Mprovedor">{#entrada.provedor#}</td>
				<td class="col-md-4 warning" ng-show="entrada.Mprovedor">{#entrada.Mprovedor#}</td>
			</tr>
		</tbody>	
	</table>
		
	<table ng-show="insumos"class="table table-striped custon-table-top-off">
		<thead>
			<tr>
				<th class="col-md-2">Codigo</th>
				<th>Descripcion</th>
				<th class="col-md-2">Cantidad</th>
				<th class="col-md-2">Modificacion</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="insumo in insumos">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.cantidad#}</td>
				<td class="warning">{#insumo.modificacion#}</td>
			</tr>
		</tbody>
	</table>
	
	<br>

</div>
<div class="modal-footer">
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
</div>