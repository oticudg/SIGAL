@extends('base')
@section('bodytag', 'ng-controller="alertController"')

@section('panel-name', 'Configuracion de alarmas')

@section('content')
	
	<div class="row">
	    <div class="col-xs-12">
	      <div class="box box-primary">
	        <div class="box-header">
	        </div>  
	        <div class="box-body">
	        	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
				<div class="row">
					<div class="col-sm-6 col-sm-offset-6">
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
							    <button class="btn btn-primary" ng-click="agregarInsumos()"><span class="glyphicon glyphicon-plus-sign"></span> Agregar</button>
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
								<th class="col-md-1">Nvl. Critico</th>
								<th class="col-md-1">Nvl. Bajo</th>
								<th class="col-md-1">Promedio</th>
								<th class="col-md-1">Eliminar</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="insumo in insumos">
								<td>{#insumo.codigo#}</td>
								<td>{#insumo.descripcion#}</td>
								<td class="danger">
									<input class="form-control text-right" type="number" ng-model="insumo.min">
								</td>
								<td class="warning">
									<input class="form-control text-right" type="number" ng-model="insumo.med">
								</td>
								<td>
									<input class="form-control text-right" type="number" ng-model="insumo.promedio">
								</td>
								<td class="text-center">
									<button class="btn btn-danger" ng-click="eliminarInsumo(insumos.indexOf(insumo))"><span class="glyphicon glyphicon-remove"></span>
									</button>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="text-right">
						<br>
						<button ng-click="guardar()" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Guardar</button>
		    		</div>
				</div>
	      </div>
	    </div>
	</div>

@endsection
