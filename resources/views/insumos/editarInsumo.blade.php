<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-pencil text-warning"></span> Editar insumo</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>
	<br>

	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				<label  class="text-muted" for="codi"><i class="fa fa-barcode"></i> Código</label>
				<input class="form-control" id="codi" type="text" placeholder="Código" ng-model="codigo">
			</div>
		</div>

		<div class="col-md-8">
			<div class="form-group">
				<label  class="text-muted" for="descripcion"><i class="fa fa-commenting"></i> Descripción</label>
				<textarea  class="form-control form-description" placeholder="Describa el nuevo insumo" ng-model="descripcion"></textarea>
			</div>
		</div>		
	</div>

</div>
<div class="modal-footer">
	<button class="btn btn-primary" ng-show="btnVisivilidad" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> Modificar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-primary" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>