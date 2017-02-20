<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-plus text-primary"></span> Nuevo documento</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>

	<div class="row">
		<div class="col-md-2">
			<div class="form-group">
				<label  class="text-muted" for="abre"><i class="fa fa-pencil"></i> Abreviatura</label>
				<input class="form-control" id="abre" type="text" placeholder="Solo 3 dígitos" ng-model="registro.abreviatura" autofocus>
			</div>
		</div>
    <div class="col-md-8">
      <div class="form-group">
        <label  class="text-muted" for="nombre"><i class="glyphicon glyphicon-folder-close"></i> Nombre</label>
        <input class="form-control" id="nombre" type="text" placeholder="Colocar el nombre o concepto del documento." ng-model="registro.nombre">
      </div>
    </div>
    <div class="col-md-2">
      <div class="form-group">
        <label  class="text-muted" for="natu"><i class="glyphicon glyphicon-transfer"></i> Naturaleza</label>
        <select class="form-control" id="natu" ng-model="registro.naturaleza">
          <option value="entrada">Entrada</option>
          <option value="salida">Salida</option>
        </select>
      </div>
    </div>
	</div>
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <label  class="text-muted" for="tipo"><i class="fa fa-cube"></i> Tipo</label>
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
				<label  class="text-muted" for="uso"><i class="fa fa-hand-lizard-o" aria-hidden="true"></i> Modo de uso</label>
				<textarea  class="form-control" placeholder="Acá debe colocar para que ha de usarse el documento, según la publicación N° 15." ng-model="registro.uso"></textarea>
			</div>
		</div>
  </div>
</div>
<div class="modal-footer">
<button class="btn btn-primary" ng-show="btnVisivilidad" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-primary" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>
