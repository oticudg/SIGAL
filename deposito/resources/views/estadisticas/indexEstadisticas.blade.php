@extends('panel')
@section('bodytag', 'ng-controller="estadisticasController"')
@section('addscript')
<script src="{{asset('js/vendor/highcharts.js')}}"></script>
<script src="{{asset('js/vendor/drilldown.js')}}"></script>
<script src="{{asset('js/vendor/exporting.js')}}"></script>
<script src="{{asset('js/vendor/dark-unica.js')}}"></script>
<script src="{{asset('js/estadisticasController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-tasks"></span> Estadísticas
	</h5>
	<br>
	
	<div ng-show="formVisivility">
		<button  class="btn btn-success" ng-click="formConsulta()"><span class="glyphicon glyphicon-list-alt"></span> Consultar</button>
		<br>
		<br>
	</div>

	<div ng-hide="formVisivility">
		
		<center><h2 class="text-title-modal">Estadisticas Personalizadas</h2></center>

		<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>

		<ul class="nav nav-tabs">
		    <li class="active"><a data-toggle="tab" class="text-enlace" href="#insumos">Insumos</a></li>
		    <li><a data-toggle="tab" class="text-enlace" href="#servicios">Servicios</a></li>
		</ul>

		<div class="tab-content">
			<div id="insumos" class="tab-pane fade in active">
				  
				<h3>Consulta por insumo</h3>

				<div class="row">
					<div class="form-group">
						<div class="col-md-6 col-md-offset-3">				
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
				</div>
				
				<br>
				
				<div class="row">
					<div class="col-md-3 col-md-offset-2">
					 	<div class="input-group">
					 		<label for="dateI">Desde:</label>
						 	<p class="input-group">
				              <input type="text" id="dateI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="dI" is-open="openedI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
				              <span class="input-group-btn">
				                <button type="button" class="btn btn-success text-white" ng-click="openI($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				              </span>
			        		</p>
			        	</div>
					</div>

					<div class="col-md-3 col-md-offset-2">
					 	<div class="input-group">
					 		<label for="dateI">Hasta:</label>
						 	<p class="input-group">
				              <input type="text" id="dateI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="dF" is-open="openedF" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
				              <span class="input-group-btn">
				                <button type="button" class="btn btn-success text-white" ng-click="openF($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				              </span>
			        		</p>
			        	</div>
					</div>
				</div>
				
				<center><button class="btn btn-success" ng-click="consultaInsumo()">Consultar</button></center>

			</div>
		    <div id="servicios" class="tab-pane fade">
		      <h3>Consulta por Servicio</h3>
			
			  <div class="row">
			  		<div class="form-group col-md-4  col-md-offset-4 text-title-modal">
			  			<select class="form-control" id="provedor" ng-model="servicio">
			  				<option value="" selected disabled>Servicio</option>
			  				<option value="{#departamento.id#}" ng-repeat="departamento in departamentos">
			  				{#departamento.nombre#}</option>
			  			</select>
					</div>
			  </div>
			  
			  <div class="row">
					<div class="col-md-3 col-md-offset-2">
					 	<div class="input-group">
					 		<label for="dateI">Desde:</label>
						 	<p class="input-group">
				              <input type="text" id="dateI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="dI" is-open="openedI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
				              <span class="input-group-btn">
				                <button type="button" class="btn btn-success text-white" ng-click="openI($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				              </span>
			        		</p>
			        	</div>
					</div>

					<div class="col-md-3 col-md-offset-2">
					 	<div class="input-group">
					 		<label for="dateI">Hasta:</label>
						 	<p class="input-group">
				              <input type="text" id="dateI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="dF" is-open="openedF" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
				              <span class="input-group-btn">
				                <button type="button" class="btn btn-success text-white" ng-click="openF($event)"><i class="glyphicon glyphicon-calendar"></i></button>
				              </span>
			        		</p>
			        	</div>
					</div>
				</div>
				
				<center><button class="btn btn-success" ng-click="consultaServicio()">Consultar</button></center>
		    </div>
		</div>

		<br>
		<hr>
		<div class="row">
			<div class="col-md-2 col-md-offset-10">
				<center>
					<button class="btn btn-warning" ng-click="formCerrar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
				</center>
			</div>
		</div>

	</div>
	
	<br>
	<br>

	<div id="graficaInicial" style="width:100%; height:500px;"></div>

@endsection
