<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-lock text-primary"></span> Cambiar contraseña</h3>
</div>
<div class="modal-body">
	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="form-group">
					<label class="text-muted" for="pass">Actual contraseña </label>
					<input class="form-control" id="pass"type="password" ng-model="data.passwordOri">
			</div>
		</div>		
	</div>

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="form-group">
					<label class="text-muted" for="pass">Nueva contraseña</label>
					<input class="form-control" id="pass"type="password" ng-model="data.password">
			</div>
		</div>		
	</div>

	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="form-group">
					<label class="text-muted" for="pass">Comfirmar nueva contraseña</label>
					<input class="form-control" id="pass"type="password" ng-model="data.password_confirmation">
			</div>
		</div>		
	</div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> Cambiar</button>
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
</div>