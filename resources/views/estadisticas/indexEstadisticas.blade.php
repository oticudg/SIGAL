@extends('base')
@section('bodytag', 'ng-controller="estadisticasController"')

@section('addscript')
<script src="{{asset('js/vendor/highcharts.js')}}"></script>
<script src="{{asset('js/vendor/drilldown.js')}}"></script>
@endsection

@section('panel-name', '<i class="fa fa-line-chart text-info"></i> Estadísticas')

@section('content')
	
<div class="nav-tabs-custom nav-pills">
        <!-- Tabs within a box -->
        <ul class="nav nav-tabs pull-right ui-sortable-handle">
          <li class="active"><a href="#insumo" data-toggle="tab" ng-click="obtenerInsumos()"><i class="glyphicon glyphicon-th text-primary"></i> Consulta por insumo</a></li>
          <li><a href="#servicio" data-toggle="tab" ng-click="obtenerInsumosv()"><i class="glyphicon glyphicon-briefcase text-primary"></i> Consulta por servicio</a></li>
        </ul>
        <div class="tab-content">
        	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
        	<br>
        	<div class="tab-pane active" id="insumo">
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
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
									
					<div class="col-sm-2">
					 	<div class="form-group">
						 	<p class="input-group">
				              <input type="text" id="dateI" class="form-control" datepicker-popup="yyyy-MM-dd" ng-model="dI" is-open="openedI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/ placeholder="Desde">
				              <span class="input-group-btn">
								  <button type="button" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Seccione una fecha" ng-click="openI($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				              </span>
			        		</p>
			        	</div>
				    </div>

					<div class="col-sm-2">
					 	<div class="form-group">
						 	<p class="input-group">
				              <input type="text" id="dateI" class="form-control" datepicker-popup="yyyy-MM-dd" ng-model="dF" is-open="openedF" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar" placeholder="Hasta" />
				              <span class="input-group-btn">
								  <button type="button" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="bottom" title="Seleccione una fecha" ng-click="openF($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				              </span>
			        		</p>
			        	</div>
					</div>

					<div class="col-sm-2 text-left"><button class="btn btn-primary" ng-click="consultaInsumo()"><i class="glyphicon glyphicon-search"></i> Consultar</button></div>
				</div>
        	</div>

        	<div class="tab-pane" id="servicio">
				<div class="row">
					<div class="col-sm-6">
						<select class="form-control" id="provedor" ng-model="servicio">
			  				<option value="" selected disabled>Servicio</option>
			  				<option value="{#departamento.id#}" ng-repeat="departamento in departamentos">
			  				{#departamento.nombre#}</option>
			  			</select>	
					</div>
									
					<div class="col-sm-2">
					 	<div class="form-group">
						 	<p class="input-group">
				              <input type="text" id="dateI" class="form-control" datepicker-popup="yyyy-MM-dd" ng-model="dI" is-open="openedI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/ placeholder="Desde">
				              <span class="input-group-btn">
								  <button type="button" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="top" title="Seccione una fecha" ng-click="openI($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				              </span>
			        		</p>
			        	</div>
				    </div>

					<div class="col-sm-2">
					 	<div class="form-group">
						 	<p class="input-group">
				              <input type="text" id="dateI" class="form-control" datepicker-popup="yyyy-MM-dd" ng-model="dF" is-open="openedF" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar" placeholder="Hasta" />
				              <span class="input-group-btn">
								  <button type="button" class="btn btn-primary text-white" data-toggle="tooltip" data-placement="bottom" title="Seleccione una fecha" ng-click="openF($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				              </span>
			        		</p>
			        	</div>
					</div>

					<div class="col-sm-2 text-left"><button class="btn btn-primary" ng-click="consultaServicio()"><i class="glyphicon glyphicon-search"></i> Consultar</button></div>
				</div> 
        	</div>
      	</div>
    </div>

    <div class="box box-primary">
		<div class="box-header">
			
		</div>	
		<div class="box-body">
			<div id="graficaInicial" style="width:100%; height:500px;"></div>
		</div>
    </div>
@endsection
