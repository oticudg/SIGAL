<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-pencil text-warning"></span> Editar usuario</h3>
</div>
<div class="modal-body modal-body-custon">
  <alert ng-show="alert" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
	<div class="row">
		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="cedula"><i class="fa fa-id-card-o" aria-hidden="true"></i> Cédula</label>
					<input class="form-control" id="cedula" type="text" placeholder="Cédula" ng-model="data.cedula">
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label  class="text-muted" for="nombre"><i class="fa fa-user" aria-hidden="true"></i> Nombre</label>
					<input class="form-control" id="nombre" type="text" placeholder="Nombre" ng-model="data.nombre">
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="apellido"><i class="fa fa-user-o" aria-hidden="true"></i> Apellido</label>
					<input class="form-control" id="apellido" type="text" placeholder="Apellido" ng-model="data.apellido">
				</div>
			</div>
		</div>
	</div>

	<div class="row">

    <div class="col-md-4">
      <div class="col-md-10 col-md-offset-1">
        <div class="form-group">
			<label class="text-muted" for="correo"><i class="fa fa-envelope" aria-hidden="true"></i> Correo</label>
          <input class="form-control" id="correo" type="text" placeholder="Correo" disabled ng-model="data.email">
        </div>
      </div>
    </div>

		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<label class="text-muted" for="pass"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Contraseña</label>
				<input class="form-control" id="pass"type="password" placeholder="Contraseña" ng-model="data.password">
			</div>
		</div>

		<div class="col-md-4">
			<div class="col-md-10 col-md-offset-1">
				<div class="form-group">
					<label class="text-muted" for="rpass"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Repetir contraseña</label>
					<input class="form-control" id="rpass" type="password" placeholder="Repetir contraseña" ng-model="data.password_confirmation">
				</div>
			</div>
		</div>
	</div>

  <div class="row">

    <div class="col-md-4">
      <div class="col-md-10 col-md-offset-1">
        <div class="form-group">
			<label class="text-muted" for="rdepo"><i class="glyphicon glyphicon-inbox"></i> Almacén</label>
          <select class="form-control" id="rdepo" ng-model="data.deposito">
            <option value="{#deposito.id#}" ng-repeat="deposito in depositos">{#deposito.nombre#}</option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="col-md-10 col-md-offset-1">
        <div class="form-group">
			<label class="text-muted" for="rdepo"><i class="glyphicon glyphicon-compressed"></i> Rol</label>
          <select class="form-control" id="rdepo" ng-model="data.rol">
            <option value="{#rol.id#}" ng-repeat="rol in roles">{#rol.nombre#}</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
     <button class="btn btn-primary" ng-show="btnVisivilidad" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> Modificar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-primary" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>
