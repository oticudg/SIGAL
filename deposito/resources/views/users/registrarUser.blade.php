<div class="modal-header">
    <h3 style="color:#54AF54;" class="modal-title"><span class="glyphicon glyphicon-plus"></span> Nuevo Usuario</h3>
</div>
<div class="modal-body modal-body-custon">
  <alert ng-show="alert" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
	<div class="row">
		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="cedula">Cedula</label>
					<input class="form-control" id="cedula" type="text" placeholder="Cedula" ng-model="data.cedula">
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label  class="text-muted" for="nombre">Nombre</label>
					<input class="form-control" id="nombre" type="text" placeholder="Nombre" ng-model="data.nombre">
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="apellido">Apellido</label>
					<input class="form-control" id="apellido" type="text" placeholder="Apellido" ng-model="data.apellido">
				</div>
			</div>
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="correo">Correo</label>
					<input class="form-control" id="correo" type="text" placeholder="Correo" ng-model="data.email">
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="pass">Contraseña</label>
					<input class="form-control" id="pass"type="password" placeholder="Contraseña" ng-model="data.password">
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="rpass">Repetir contraseña</label>
					<input class="form-control" id="rpass" type="password" placeholder="Repetir contraseña" ng-model="data.password_confirmation">
				</div>
			</div>
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="rdepo">Almacén</label>
					<select class="form-control" id="rdepo" ng-model="data.deposito">
						<option value="" selected disabled>Almacén</option>
						<option value="{#deposito.id#}" ng-repeat="deposito in depositos">{#deposito.nombre#}</option>
					</select>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="rdepo">Rol</label>
					<select class="form-control" id="rdepo" ng-model="data.rol">
						<option value="" selected disabled>Rol</option>
						<option value="{#rol.id#}" ng-repeat="rol in roles">{#rol.nombre#}</option>
					</select>
				</div>
			</div>
		</div>
	</div>

</div>
<div class="modal-footer">
    <button class="btn btn-success" ng-show="btnVisivilidad" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>
