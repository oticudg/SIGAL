<div class="modal-header">
    <h3 class="modal-title text-title-modal"><span class="glyphicon glyphicon-plus"></span> Nuevo Insumo</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>
	
	<div class="row">
		<div class="col-md-4">
			<input class="form-control" type="text" placeholder="Codigo" ng-model="codigo">
		</div>
			
		<div class="col-md-8">
			<textarea  class="form-control form-description" placeholder="Descripcion" ng-model="descripcion"></textarea>
		</div>
	</div>
</div>
<div class="modal-footer">
<button class="btn btn-success" ng-show="btnVisivilidad" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>