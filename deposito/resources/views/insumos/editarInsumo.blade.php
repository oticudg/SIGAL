<div class="modal-header">
    <h3 class="modal-title text-title-modal"><span class="glyphicon glyphicon-pencil"></span> Editar Insumo</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>
	<br>

	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label  class="text-muted" for="codi">Codigo</label>
				<input class="form-control" id="codi" type="text" placeholder="Codigo" ng-model="codigo">
			</div>
		</div>

		<div class="col-md-8">
			<div class="form-group">
				<label  class="text-muted" for="descripcion">Descripción</label>
				<textarea  class="form-control form-description" placeholder="Descripción" ng-model="descripcion"></textarea>
			</div>
		</div>		
	</div>

</div>
<div class="modal-footer">
	<button class="btn btn-success" ng-show="btnVisivilidad" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> modificar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>