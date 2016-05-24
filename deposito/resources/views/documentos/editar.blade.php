<div class="modal-header">
    <h3 class="modal-title text-title-modal"><span class="glyphicon glyphicon-plus"></span> Nuevo Documento</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>

	<div class="row">
		<div class="col-md-2">
			<div class="form-group">
				<label  class="text-muted" for="abre">Abreviatura</label>
				<input class="form-control" id="abre" type="text" placeholder="Abreviatura" ng-model="registro.abreviatura">
			</div>
		</div>
    <div class="col-md-8">
      <div class="form-group">
        <label  class="text-muted" for="nombre">Nombre</label>
        <input class="form-control" id="nombre" type="text" placeholder="Nombre" ng-model="registro.nombre">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <label  class="text-muted" for="natu">Naturaleza</label>
        <select class="form-control" id="natu" ng-model="registro.naturaleza" disabled>
          <option value="entrada">Entrada</option>
          <option value="salida">Salida</option>
        </select>
      </div>
    </div>
	</div>
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <label  class="text-muted" for="tipo">Tipo</label>
        <select class="form-control" id="tipo" ng-model="registro.tipo">
          <option value="proveedor">Proveedor</option>
          <option value="servicio">Servicio</option>
          <option value="deposito">Deposito</option>
          <option value="interno">Interno</option>
        </select>
      </div>
    </div>
    <div class="col-md-10">
			<div class="form-group">
				<label  class="text-muted" for="uso">Modo de uso</label>
				<textarea  class="form-control" placeholder="Uso" ng-model="registro.uso"></textarea>
			</div>
		</div>
  </div>
</div>
<div class="modal-footer">
<button class="btn btn-success" ng-show="btnVisivilidad" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>
