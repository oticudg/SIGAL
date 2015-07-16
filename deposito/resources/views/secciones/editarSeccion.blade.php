<div class="modal-header">
    <h3 style="color:#54AF54;" class="modal-title">Editar Sección</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Nombre" ng-model="nombre">
		</div>
	</div>
</div>
<div class="modal-footer">
    <button class="btn btn-success" ng-show="btnVisivilidad" ng-click="modificar()">Modificar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()">Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()">Ok</button>	
</div>