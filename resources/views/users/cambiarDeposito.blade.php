<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-inbox text-primary"></span> Cambiar Almacén</h3>
</div>
<div class="modal-body">
	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
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
    <button class="btn btn-primary" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> Cambiar</button>
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
</div>