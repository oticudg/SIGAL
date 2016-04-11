<div class="modal-header">
    <div class="row">
    	<div class="col-md-6">
		    <h3 class="modal-title text-title-modal">
		    	<span class="glyphicon glyphicon glyphicon-search"></span> Buscador avanzado de entradas
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
    <div class="form-groupm col-md-3">
      <label class="text-muted">Tipo de entrada</label>
      <select class="form-control" ng-model="data.type">
        <option value="all">Todas</option>
        <option value="orden">Ordenes</option>
        <option value="donacion">Donaciones</option>
        <option value="devolucion">Devolucion</option>
      </select>
    </div>

    <div class="form-groupm col-md-3">
      <label class="text-muted">Orden de registros</label>
      <select class="form-control" ng-model="data.orden">
        <option value="desc">descendente</option>
        <option value="asc">ascendente</option>
      </select>
    </div>

    <div class="form-groupm col-md-3">
      <label class="text-muted">Usuario</label>
      <select class="form-control" ng-model="data.user">
        <option value="{#usuario.id#}" ng-repeat="usuario in usuarios">{#usuario.nombre + ' ' + usuario.apellido#}</option>
      </select>
    </div>

  </div>
  <br>
  <div class="row">
    <div class="form-group col-md-12">
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
  </div>
  <div class="row" >
    <div class="col-md-6" ng-show="insumoSelect.selected">
      <center ng-hide="amountp">
        <button type="button" class="btn btn-success" ng-click="amountR()">Rango de cantidad</button>
      </center>

      <div class="panel panel-default" ng-show="amountp">
          <div class="panel-heading">Rango de cantidad</div>
          <div class="panel-body">
            <div class="row">
              <div class="form-group col-md-6">
                <label class="text-muted" for="">Desde</label>
                <input type="number" class="form-control" ng-model="data.cantidadI">
              </div>
              <div class="form-group col-md-6">
                <label class="text-muted" for="">hasta</label>
                <input type="number" class="form-control" ng-model="data.cantidadF">
              </div>
            </div>
          </div>
       </div>
     </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-6">
      <center>
        <button type="button" ng-hide ="datep" class="btn btn-success" ng-click="dateR()">Rango de fecha</button>
      </center>
      <div class="panel panel-default" ng-show="datep">
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
     <div class="col-md-6">
       <center>
         <button type="button" ng-hide="timep" class="btn btn-success" ng-click="hourR()">Rango de hora</button>
       </center>
       <div ng-show="timep" class="panel panel-default">
           <div class="panel-heading">Rango de hora</div>
           <div class="panel-body">
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
