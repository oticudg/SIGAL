@extends('panel')
@section('bodytag', 'ng-controller="inventarioController"')
@section('addscript')
<script src="{{asset('js/herramientasInventarioController.js')}}"></script>
@endsection

@section('front-page')
	
	<nav class="nav-ubication">
		<ul class="nav-enlaces">	
			<li><span class="glyphicon glyphicon-th-list"></span> Inventario</li>
			<li class="nav-active"><span class="glyphicon glyphicon-wrench"></span> Herramientas</li>
		</ul>
	</nav>

	<br>
	
	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>

	<ul class="nav nav-tabs">
		<li class="active" ng-click="registrosEntradas('todas')"><a data-toggle="tab" class="text-enlace" href="#alarmas">Alarmas</a></li>

		<li><a data-toggle="tab" class="text-enlace" href="#carga">Cargas de inventario</a></li>
	</ul>
	
	<div class="tab-content">
		{{--Panel de registros de alarmas--}}
		<div id="alarmas" class="tab-pane fade in active">
			<center>
				<h3 class="text-success">Configuracion de alarmas</h3>
			</center>
			<br><br>					
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="input-group">

		          			<ui-select ng-model="insumoSelect.selected"
						             ng-disabled="disabled"
						             reset-search-input="true">
						    <ui-select-match placeholder="Ingrese una Descripción o un codigo">
						    {#$select.selected.descripcion#}</ui-select-match>
						    <ui-select-choices repeat="insumo in listInsumos track by $index"
						             refresh="refreshInsumos($select.search)"
						             refresh-delay="0">
						      <div ng-bind-html="insumo.descripcion | highlight: $select.search"></div>
						       <small ng-bind-html="insumo.codigo"></small>
						    </ui-select-choices>
						    </ui-select>

						<div class="input-group-btn">
						    <button class="btn btn-success" ng-click="agregarInsumos()"><span class="glyphicon glyphicon-plus-sign"></span> Agregar</button>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div ng-show="existInsumos()">
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-2">Codigo</th>
							<th>Descripción</th>
							<th class="col-md-2">Nivel Critico</th>
							<th class="col-md-2">Nivel Bajo</th>
							<th class="col-md-1">Eliminar</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="insumo in insumos">
							<td>{#insumo.codigo#}</td>
							<td>{#insumo.descripcion#}</td>
							<td class="danger">
								<input class="form-control text-center" type="number" ng-model="insumo.min">
							</td>
							<td class="warning">
								<input class="form-control text-center" type="number" ng-model="insumo.med">
							</td>
							<td>
								<button class="btn btn-danger" ng-click="eliminarInsumo(insumos.indexOf(insumo))"><span class="glyphicon glyphicon-remove"></span>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
				<br>
				<center>	
					<button ng-click="guardar()" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Guardar</button>
	    		</center>
    		</div>
		</div>

		{{--Panel de registros de inventario--}}
		<div id="carga" class="tab-pane fade">

		</div>
	</div>

@endsection
