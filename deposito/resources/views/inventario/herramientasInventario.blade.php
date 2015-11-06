<div class="modal-header">
    <h3 class="modal-title text-title-modal"><span class="glyphicon glyphicon-wrench"></span> Herramientas</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>
	
	<tabset>
    	<tab heading="Alarmas">
			<center>
				<h3 class="text-success">Configuracion de alarmas</h3>
			</center>
			<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
			<br><br>					
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="input-group">

		          			<ui-select ng-model="insumoSelect.selected"
						             ng-disabled="disabled"
						             reset-search-input="true">
						    <ui-select-match placeholder="Ingrese una Descripción o un codigo">
						    {#$select.selected.descripcion#}</ui-select-match>
						    <ui-select-choices repeat="insumo in listInsumos track by $index"
						             refresh="refreshInsumos($select.search)"
						             refresh-delay="0">
						      <div ng-bind-html="insumo.descripcion | highlight: $select.search"></div>
						       <small ng-bind-html="insumo.codigo"></small>
						    </ui-select-choices>
						    </ui-select>

						<div class="input-group-btn">
						    <button class="btn btn-success" ng-click="agregarInsumos()"><span class="glyphicon glyphicon-plus-sign"></span> Agregar</button>
						</div>
					</div>
				</div>
			</div>
			<br>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Descripción</th>
						<th class="col-md-2">Nivel Critico</th>
						<th class="col-md-2">Nivel Bajo</th>
						<th class="col-md-1">Eliminar</th>
					</tr>
				</thead>
				<tbody>
					<tr ng-repeat="insumo in insumos">
						<td>{#insumo.descripcion#}</td>
						<td class="danger">
							<input class="form-control text-center" type="number" ng-model="insumo.min">
						</td>
						<td class="warning">
							<input class="form-control text-center" type="number" ng-model="insumo.med">
						</td>
						<td>
							<button class="btn btn-danger" ng-click="eliminarInsumo(insumos.indexOf(insumo))"><span class="glyphicon glyphicon-remove"></span>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
			<br>
			<center>	
				<button ng-show="existInsumos()" ng-click="guardar()" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Guardar</button>
    		</center>
    	</tab>
  	</tabset>
	
</div>

<div class="modal-footer">
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
</div>