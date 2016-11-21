<div class="modal-header">
    <div class="row">
    	<div class="col-md-6">
		    <h3 class="modal-title text-title-modal">
		    	<span class="glyphicon glyphicon glyphicon-search text-primary"></span> Buscador avanzado
		    </h3>
		</div>
	    <div class="col-md-4">
	    	<div class="row">
		    	<div class="col-md-offset-2 col-md-11">
					<h3 class="modal-title text-title-modal" ng-show="entrada.orden">
				    	N° Orden: <strong>{#entrada.orden#}</strong>
				    </h3>
			    </div>
			</div>
		</div>
	</div>
</div>
<div class="modal-body">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading text-center">Filtros por exclusión</div>
          <div class="panel-body">
            <div class="row">
             <div class="form-groupm col-md-2">
               <label class="text-muted">Tipo de movimiento</label>
               <select class="form-control" ng-change="moviType(data.type)" ng-model="data.type">
                 <option value="all">Todos</option>
                 <option value="entrada">Entrada</option>
                 <option value="salida">Salida</option>
               </select>
             </div>
              <div class="form-group col-md-3">
                  <label class="text-muted">Usuario</label>
                  <ui-select ng-model="userSelect.selected"
                           ng-disabled="disabled"
                           reset-search-input="true">
                  <ui-select-match placeholder="Seleccione un usuario">
                  {#$select.selected.nombre#}</ui-select-match>
                  <ui-select-choices repeat="usuario in usuarios | filter:$select.search track by usuario.id">
                    <div ng-bind-html="usuario.nombre | highlight: $select.search"></div>
                  </ui-select-choices>
                  </ui-select>
              </div>
              <div class="col-md-3">
                <label class="text-muted">Concepto</label>
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
          		<div class="form-group col-md-4" ng-show="panelTerceros">
                  <label class="text-muted">Tercero</label>
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
   </div>
 </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading text-center">Filtros por rangos de valores</div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-2 col-md-offset-2">
                <button type="button" class="btn btn-primary btn-block" ng-click="dateSearch()">Rango de fecha</button>
              </div>

              <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" ng-click="timeSearch()">Rango de hora</button>
              </div>

              <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" ng-click="amounSearch()">Rango Mov.</button>
              </div>

              <div class="col-md-2">
                <button type="button" class="btn btn-primary btn-block" ng-click="amounESearch()">Rango Exist.</button>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-6" ng-show="datep">
                <div class="panel panel-default">
                    <div class="panel-heading">Rango de fecha</div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="form-group col-md-6">
                          <label class="text-muted" for="fechaI">Hasta</label>
                          <p class="input-group">
                            <input type="text" id="fechaI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="fechaI" is-open="openedI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
                                <span class="input-group-btn">
                                  <button type="button" class="btn btn-primary text-white" ng-click="openI($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                          </p>
                        </div>
                        <div class="form-group col-md-6">
                          <label class="text-muted" for="fechaF">Hasta</label>
                          <p class="input-group">
                            <input type="text" id="fechaF" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="fechaF" is-open="openedF" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
                                <span class="input-group-btn">
                                  <button type="button" class="btn btn-primary text-white" ng-click="openF($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                                </span>
                          </p>
                        </div>
                      </div>
                    </div>
                 </div>
               </div>
               <div class="col-md-6" ng-show="timep">
                 <div class="panel panel-default">
                     <div class="panel-heading">Rango de hora</div>
                     <div class="panel-body" style="margin-left:80px;">
                       <div class="row">
                         <div class="col-md-6 form-group">
                           <label for="" class="text-muted">Desde</label>
                           <timepicker ng-model="timeI" show-meridian="false"></timepicker>
                         </div>
                         <div class="col-md-6 form-group">
                           <label for="" class="text-muted">Hasta</label>
                           <timepicker ng-model="timeF" show-meridian="false"></timepicker>
                         </div>
                       </div>
                     </div>
                  </div>
                </div>
            </div>
            <div class="row">
            <div class="col-md-6" ng-show="amounp">
              <div class="panel panel-default">
                <div class="panel-heading">Rango del movimiento</div>
                <div class="panel-body">
                  <div class="text-center">
                    <div class="form-group col-md-6">
                      <label class="text-muted" for="">Desde</label>
                      <input type="number" class="form-control text-center" ng-model="data.cantidadI">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="text-muted" for="">hasta</label>
                      <input type="number" class="form-control text-center" ng-model="data.cantidadF">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6" ng-show="amounEp">
              <div class="panel panel-default">
                <div class="panel-heading">Rango de existencia</div>
                <div class="panel-body">
                  <div class="text-center">
                    <div class="form-group col-md-6">
                      <label class="text-muted" for="">Desde</label>
                      <input type="number" class="form-control text-center" ng-model="data.existenciaI">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="text-muted" for="">hasta</label>
                      <input type="number" class="form-control text-center" ng-model="data.existenciaF">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
       </div>
     </div>
    </div>
  </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-click="buscar()"><span class="glyphicon glyphicon glyphicon-search"></span> Buscar</button>
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
</div>
