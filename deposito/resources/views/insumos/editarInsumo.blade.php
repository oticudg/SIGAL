<div class="modal-header">
    <h3 class="modal-title text-title-modal"><span class="glyphicon glyphicon-pencil"></span> Editar Insumo</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>
	
	<div class="row">
		<div class="col-md-4 col-md-offset-4">	
			<center>
				<h3>Codigo</h3>
				<h3><strong class="text-success">{#codigo#}</strong></h3>
			</center>
		</div>
	</div>

	<center>
		<div>
			<textarea  class="form-control form-description" placeholder="Descripcion" ng-model="descripcion"></textarea>
		</div>
	</center>

</div>
<div class="modal-footer">
	<button class="btn btn-success" ng-show="btnVisivilidad" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> modificar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>