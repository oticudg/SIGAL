@extends('base')
@section('bodytag', 'ng-controller="alertController"')

@section('panel-name', '<i class="glyphicon glyphicon-bell text-info"></i> Configuración de alarmas')

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
									<ui-select-match placeholder="Ingrese una descripción o un código">
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
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="col-md-2"><i class="fa fa-barcode"></i> Código</th>
								<th><i class="fa fa-commenting"></i> Descripción</th>
								<th class="col-md-2"><i class="fa fa-cart-arrow-down"></i> Nivel crítico</th>
								<th class="col-md-2"><i class="fa fa-caret-square-o-down"></i> Nivel bajo</th>
								<th class="col-md-2"><i class="fa fa-sort-numeric-asc"></i> Promedio</th>
								<th class="col-md-2"><i class="glyphicon glyphicon-trash"></i> Eliminar</th>
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
									<button class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Presione para remover" ng-click="eliminarInsumo(insumos.indexOf(insumo))"><span class="glyphicon glyphicon-remove"></span>
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
