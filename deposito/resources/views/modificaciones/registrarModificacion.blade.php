<div class="modal-header">
	<h3 ng-hide="status" class="modal-title text-title-modal"><span class="glyphicon glyphicon-plus"></span> Nueva Modificación</h3>
    <h3 ng-show="status" class="modal-title text-title-modal">
    	<span class="glyphicon glyphicon-circle-arrow-down"></span> Pro-Forma de Entrada: <strong>{#entrada.codigo#}</strong>
    </h3>
</div>
<div class="modal-body">

	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
	
	<div ng-hide="status">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="input-group">
			  		<input type="text" class="form-control text-center" ng-model="codigo" placeholder="Codigo de Pro-Forma">
					<div class="input-group-btn">
					    <button class="btn btn-success" ng-click="ubicarEntrada()"><span class="glyphicon glyphicon-search"></span></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div ng-show="status">
	
		<table class="table table-bordered custon-table-bottom-off">
			<thead>
				<th class="col-md-2">N° de Orden</th>
				<th>Proveedor</th>
			</thead>
			<tbody>
				<td>{#entrada.orden#}</td>
				<td>{#entrada.provedor#}</td>
			</tbody>
		</table>

		<table class="table table-bordered custon-table-bottom-off custon-table-top-off">
			<thead>
				<th class="col-md-2">Modificar Orden</th>
				<th><span ng-show="orden">Modificar Proveedor</span></th>
			</thead>
			<tbody>
				<td class="warning">
					<input class="form-control text-center" type="text" 
					placeholder="N° de orden" ng-model="orden"></td>
				<td ng-show="orden" class="warning">
					<select class="form-control" ng-model="provedor">
						<option value=" " selected >Proveedor</option>
						<option value="{#provedor.id#}" ng-repeat="provedor in provedores">{#provedor.nombre#}</option>
					</select>
				</td>
			</tbody>
		</table>
		
		<table class="table table-striped custon-table-top-off">
			<thead>
				<tr>
					<th>Codigo</th>
					<th>Descripción</th>
					<th>Cantidad</th>
					<th>Modificacion</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-class="insumo.style" ng-repeat="insumo in insumos">
					<td class="col-md-2">{#insumo.codigo#}</td>
					<td>{#insumo.descripcion#}</td>
					<td class="col-md-2">{#insumo.cantidad#}</td>
					<td class="col-md-2 warning">
						<input class="form-control text-center" type="number" ng-model="insumo.modificacion">
					</td>
				</tr>
			</tbody>
		</table>
	</div>

</div>
<div class="modal-footer">
<button class="btn btn-success" ng-show="btnVisivilidad && status" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>