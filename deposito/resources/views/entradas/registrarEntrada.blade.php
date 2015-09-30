@extends('panel')
@section('bodytag', 'ng-controller="registroEntradaController"')
@section('addscript')
<script src="{{asset('js/registroEntradaController.js')}}"></script>
@endsection

@section('front-page')

	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-circle-arrow-down"></span> Registro de Entrada
	</h5>

	<center>
		<h3 class="text-title-modal">Registro de Pro-Forma de entrada</h3>
	</center>
	
	<br>

	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
	
	<div class="row">
		<div class="col-md-3">
			<input class="form-control" type="text" placeholder="Orden de Compra N°" ng-model="codigo">
		</div>
	</div>

	<br>
	<br>

	<div class="row">
		<div class="form-group col-md-3 text-title-modal">
  			<select class="form-control" id="provedor" ng-model="provedor">
  				<option value="" selected disabled>Proveedor</option>
  				<option value="{#provedore.id#}" ng-repeat="provedore in provedores">{#provedore.nombre#}</option>
  			</select>
		</div>
		<div class="col-md-4 col-md-offset-1">
			<div class="input-group">
				<ui-select ng-model="insumoSelect.selected" theme="bootstrap">
		            <ui-select-match placeholder="Indique un insumo">{#$select.selected.codigo#}</ui-select-match>
		            <ui-select-choices repeat="item in listInsumos | filter: $select.search">
		              <div ng-bind-html="item.descripcion | highlight: $select.search"></div>
		              <small ng-bind-html="item.codigo | highlight: $select.search"></small>
		            </ui-select-choices>
          		</ui-select>

				<div class="input-group-btn">
				    <button class="btn btn-success" ng-click="agregarInsumos()"><span class="glyphicon glyphicon-plus-sign"></span> Agregar</button>
				</div>
			</div>
		</div>
	</div>
	
	<br>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>Codigo</th>
				<th>Descripción</th>
				<th>Cantidad</th>
				<th>Eliminar</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-repeat="insumo in insumos">
				<td class="col-md-2">{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td class="col-md-2">
					<input class="form-control text-center" type="number" ng-model="insumo.cantidad">
				</td>
				<td class="col-md-1">
					<button class="btn btn-danger" ng-click="eliminarInsumo(insumos.indexOf(insumo))"><span class="glyphicon glyphicon-remove"></span>
					</button>
				</td>
			</tr>
		</tbody>
	</table>
	
	<br>

	<center>
		<button class="btn btn-success" ng-click="registroEntrada()"><span class="glyphicon glyphicon-ok-sign"></span> Registar</button>
	</center>	

@endsection
