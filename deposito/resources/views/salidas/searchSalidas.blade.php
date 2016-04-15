<div class="modal-header">
    <div class="row">
    	<div class="col-md-6">
		    <h3 class="modal-title text-title-modal">
		    	<span class="glyphicon glyphicon glyphicon-search"></span> Buscador avanzado de salidas
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

    <div class="form-group col-md-3">
      <label class="text-muted">Orden de registros</label>
      <select class="form-control" ng-model="data.orden">
        <option value="desc">descendente</option>
        <option value="asc">ascendente</option>
      </select>
    </div>

    <div class="form-group col-md-3">
        <label class="text-muted">Usuario</label>
        <ui-select ng-model="userSelect.selected"
                 ng-disabled="disabled"
                 reset-search-input="true">
        <ui-select-match placeholder="Seleccione un usuario">
        {#$select.selected.nombre + ' ' + $select.selected.apellido#}</ui-select-match>
        <ui-select-choices repeat="usuario in usuarios | filter:$select.search track by usuario.id">
          <div ng-bind-html="(usuario.nombre + ' ' + usuario.apellido) | highlight: $select.search"></div>
        </ui-select-choices>
        </ui-select>
    </div>

    <div class="form-group col-md-6">
        <label class="text-muted">Servicio</label>
        <ui-select ng-model="departSelect.selected"
                 ng-disabled="disabled"
                 reset-search-input="true">
        <ui-select-match placeholder="Seleccione un servicio">
        {#$select.selected.nombre#}</ui-select-match>
        <ui-select-choices repeat="departamento in departamentos | filter:$select.search track by departamento.id">
          <div ng-bind-html="departamento.nombre| highlight: $select.search"></div>
        </ui-select-choices>
        </ui-select>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3">
      <button type="button" ng-click="insumoSearch()" class="btn btn-success btn-block">Buscar por insumo</button>
    </div>

    <div class="col-md-3">
      <button type="button" class="btn btn-success btn-block" ng-click="dateSearch()">Buscar por rango de fecha</button>
    </div>

    <div class="col-md-3">
      <button type="button" class="btn btn-success btn-block" ng-click="timeSearch()">Rango de hora</button>
    </div>
  </div>
  <br>
  <div class="row" ng-show="insumop">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">Buscar insumo</div>
        <div class="panel-body">
          <div class="form-group col-md-8">
              <label class="text-muted">Insumo</label>
              <ui-select ng-model="insumoSelect.selected"
                       ng-disabled="disabled"
                       reset-search-input="true">
              <ui-select-match placeholder="Seleccione un insumo">
              {#$select.selected.descripcion#}</ui-select-match>
              <ui-select-choices repeat="insumo in listInsumos track by $index"
                       refresh="refreshInsumos($select.search)"
                       refresh-delay="0">
                <div ng-bind-html="insumo.descripcion | highlight: $select.search"></div>
                 <small ng-bind-html="insumo.codigo"></small>
              </ui-select-choices>
              </ui-select>
          </div>
          <div class="col-md-4 text-center" ng-show="insumoSelect.selected">
            <h3 style="margin-top:0px;" class="text-muted">Rango por cantidad</h3>
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
  </div>
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
                        <button type="button" class="btn btn-success text-white" ng-click="openI($event)"><i class="glyphicon glyphicon-calendar"></i></button>
                      </span>
                </p>
              </div>
              <div class="form-group col-md-6">
                <label class="text-muted" for="fechaF">Hasta</label>
                <p class="input-group">
                  <input type="text" id="fechaF" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="fechaF" is-open="openedF" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-success text-white" ng-click="openF($event)"><i class="glyphicon glyphicon-calendar"></i></button>
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
</div>
<div class="modal-footer">
    <button class="btn btn-success" ng-click="buscar()"><span class="glyphicon glyphicon glyphicon-search"></span> Buscar</button>
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
</div>
