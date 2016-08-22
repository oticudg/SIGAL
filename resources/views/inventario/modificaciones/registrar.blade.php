<div class="modal-header">
  <div class="row">
    <div class="col-md-6">
	     <h3 class="modal-title text-title-modal">
	    	   <span class="glyphicon glyphicon glyphicon-search"></span> Nueva modificacion
	     </h3>
		</div>
	</div>
</div>
<div class="modal-body">
  <alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
  <div class="row" ng-hide="uiStatus">
    <div class="col-md-offset-4 col-md-4">
      <div class="panel panel-default">
          <div class="panel-heading">Codigo del movimiento</div>
          <div class="panel-body">
            <div class="input-group">
                <input type="text" class="form-control text-center" ng-model="code">
                <span class="input-group-addon btn-success text-white" ng-click="search()"><span class="glyphicon glyphicon-search"></span></span>
            </div>
          </div>
       </div>
    </div>
  </div>

  <div ng-show="uiStatus">
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">Concepto</div>
            <div class="panel-body">
              <ui-select ng-model="documentoSelect.selected"
        							 ng-disabled="disabled"
        							 reset-search-input="true" on-select="searchTerceros()">
        			<ui-select-match placeholder="Seleccione un concepto">
        			{#$select.selected.nombre#}</ui-select-match>
        			<ui-select-choices repeat="documento in documentos | filter:$select.search track by documento.id">
        				<div ng-bind-html="documento.nombre | highlight: $select.search"></div>
        			</ui-select-choices>
        			</ui-select>
            </div>
         </div>
      </div>

      <div class="col-md-6" ng-show="panelTerceros">
        <div class="panel panel-default">
            <div class="panel-heading">Tercero</div>
            <div class="panel-body">
              <ui-select ng-model="terceroSelect.selected"
      								 ng-disabled="disabled"
      								 reset-search-input="true">
      				<ui-select-match placeholder="Seleccione un tercero">
      				{#$select.selected.nombre#}</ui-select-match>
      				<ui-select-choices repeat="tercero in terceros | filter:$select.search">
      					<div ng-bind-html="tercero.nombre | highlight: $select.search"></div>
      				</ui-select-choices>
      				</ui-select>
            </div>
         </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Movimiento</div>
            <div class="panel-body">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <th class="col-md-1">Fecha</th>
                    <th class="col-md-1">Codigo</th>
                    <th class="col-md-1">Concepto</th>
                    <th class="col-md-1">Tipo</th>
                    <th class="col-md-5">Tercero</th>
                    @if( Auth::user()->hasPermissions(['inventory_movements']))
                      <th class="col-md-1">Detalles</th>
                    @endif
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{#movimiento.fecha#}</td>
                    <td>{#movimiento.codigo | codeforma#}</td>
                    <td><span class="text-enlace" tooltip="{#movimiento.concepto#}">{#movimiento.abreviatura#}</span></td>
                    <td>{#movimiento.type#}</td>
                    <td>{#movimiento.tercero#}</td>
                    @if( Auth::user()->hasPermissions(['inventory_movements']))
                      <td><button class="btn btn-warning" ng-click="detallesNota(movimiento.type,movimiento.id)"><span class="glyphicon glyphicon-plus-sign"></span></button></td>
                    @endif
                  </tr>
                </tbody>
              </table>
            </div>
         </div>
      </div>
    </div>
  </div>

</div>
<div class="modal-footer">
    <button class="btn btn-success"  ng-show="uiStatus" ng-click="update()"><span class="glyphicon glyphicon-ok-sign"></span> Modificar</button>
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
</div>
