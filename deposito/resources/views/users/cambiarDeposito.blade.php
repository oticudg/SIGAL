<div class="modal-header">
    <h3 class="modal-title text-title-modal"><span class="glyphicon glyphicon-inbox"></span> Cambiar Almacén</h3>
</div>
<div class="modal-body">
	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
	<center>
		<h3 class="text-title-modal">Almacén</h3>
		<h3><strong class="text-muted">{#deposito#}</strong><h3>
	</center>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<select class="form-control" ng-model="depositoM">
				<option value="" selected disabled>Almacén</option>
				<option value="{#depositoG.id#}" ng-repeat="depositoG in depositos">{#depositoG.nombre#}</option>
			</select>
		</div>		
	</div>
</div>
<div class="modal-footer">
    <button class="btn btn-success" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> Cambiar</button>
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
</div>