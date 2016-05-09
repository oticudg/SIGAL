<div class="modal-header">
    <div class="row">
    	<div class="col-md-6">
		    <h3 class="modal-title text-title-modal">
		    	<span class="glyphicon glyphicon glyphicon-search"></span> Buscador avanzado
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
   <div class="form-groupm col-md-2">
     <label class="text-muted">Tipo de movimiento</label>
     <select class="form-control" ng-change="moviType(type)" ng-model="type">
       <option value="all">Todos</option>
       <option value="entrada">Entrada</option>
       <option value="salida">Salida</option>
     </select>
   </div>

    <div class="form-groupm col-md-2" ng-show="comcpp">
      <label class="text-muted">Tipo de entrada</label>
      <select class="form-control" ng-change="comcpType(comcp)" ng-model="comcp">
        <option value="all">Todas</option>
        <option value="orden">Ordenes</option>
        <option value="donacion">Donaciones</option>
        <option value="devolucion">Devolucion</option>
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

    <div class="form-group col-md-5"  ng-show="provedorp">
        <label class="text-muted">Proveedor</label>
        <ui-select ng-model="proveSelect.selected"
                 ng-disabled="disabled"
                 reset-search-input="true">
        <ui-select-match placeholder="Seleccione un proveedor">
        {#$select.selected.nombre#}</ui-select-match>
        <ui-select-choices repeat="provedor in provedores | filter:$select.search track by provedor.id">
          <div ng-bind-html="provedor.nombre | highlight: $select.search"></div>
        </ui-select-choices>
        </ui-select>
    </div>

  </div>

  <div class="row">
    <div class="col-md-2">
      <button type="button" class="btn btn-success btn-block" ng-click="dateSearch()">Rango de fecha</button>
    </div>

    <div class="col-md-2">
      <button type="button" class="btn btn-success btn-block" ng-click="timeSearch()">Rango de hora</button>
    </div>

    <div class="col-md-2">
      <button type="button" class="btn btn-success btn-block" ng-click="amounSearch()">Rango de  cantidad</button>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-4" ng-show="datep">
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
     <div class="col-md-4" ng-show="amounp">
       <div class="panel panel-default">
         <div class="panel-heading">Rango por cantidad</div>
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
     <div class="col-md-4" ng-show="timep">
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
