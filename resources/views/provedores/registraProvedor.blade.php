<div class="modal-header">
    <h3  class="modal-title"><span class="glyphicon glyphicon-plus text-primary"></span> Nuevo proveedor</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>

	<div class="row">
		<div class="col-md-3 col-md-offset-1">
			<div class="form-group">
				<label  class="text-muted" for="rif"><i class="fa fa-certificate"></i> RIF</label>
				<input class="form-control" id="rif" type="text" placeholder="J-888888888" ng-model="rif">
			</div>
		</div>

		<div class="col-md-7">
			<div class="form-group">
				<label  class="text-muted" for="nombre"><i class="glyphicon glyphicon-folder-open"></i> Nombre</label>
				<input class="form-control" id="nombre" type="text" placeholder="Nombre" ng-model="nombre">
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
  <button class="btn btn-primary" ng-show="btnVisivilidad" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-primary" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>