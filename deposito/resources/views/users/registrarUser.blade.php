<div class="modal-header">
    <h3 style="color:#54AF54;" class="modal-title">Nuevo Usuario</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>


	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Nombre" ng-model="nombre">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Apellido" ng-model="apellido">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Cedula" ng-model="cedula">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Email" ng-model="email">
		</div>
	</div>

	<br>
	

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="password" placeholder="Contraseña" ng-model="password">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="password" placeholder="Repetir contraseña" ng-model="password_confirmation">
		</div>
	</div>

	<br>
	
	<div class="row">
		<div class="col-md-3 col-md-offset-3">

			<select name="rol" class="form-control" ng-model="rol">
				<option value="">Departamento</option>
				<option value="farmacia">Farmacia</option>
				<option value="alimentacion">Alimentacion</optison>
			</select>
		</div>

		<div class="col-md-3">		
			<select name="rango" class="form-control" ng-model="rango">
				<option value="">Rango</option>
				<option value="director">Director</option>
				<option value="jefe">Jefe</option>
				<option value="empleado">Empleado</option>
			</select>
		</div>

	</div>


</div>
<div class="modal-footer">
    <button class="btn btn-success" ng-show="btnVisivilidad" ng-click="registrar()">Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()">Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()">Ok</button>	
</div>