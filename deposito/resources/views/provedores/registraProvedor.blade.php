<div class="modal-header">
    <h3 style="color:#54AF54;" class="modal-title">Nuevo Provedor</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Rif" ng-model="rif">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Nombre" ng-model="nombre">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Telefono" ng-model="telefono">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Contacto" ng-model="contacto">
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
			<textarea style="width:420px; max-width:420px;" class="form-control"  placeholder="Direccion" ng-model="direccion"></textarea>
		</div>
	</div>

</div>
<div class="modal-footer">
    <button class="btn btn-success" ng-show="btnVisivilidad" ng-click="registrar()">Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()">Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()">Ok</button>	
</div>