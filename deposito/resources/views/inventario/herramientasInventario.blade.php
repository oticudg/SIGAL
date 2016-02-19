@extends('panel')
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

	<ul class="nav nav-tabs">
		<li class="active" ng-click="registrosEntradas('todas')"><a data-toggle="tab" class="text-enlace" href="#alarmas">Alarmas</a></li>

		<li><a data-toggle="tab" class="text-enlace" href="#carga">Cargas de inventario</a></li>
	</ul>
	
	<div class="tab-content">
		{{--Panel de registros de alarmas--}}
		<div id="alarmas" class="tab-pane fade in active" ng-controller="alertController">
			<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
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
		<div id="carga" class="tab-pane fade" ng-controller="cargaInvController">
			
			<div ng-show="loader" class="div_loader">
				<div id="img_loader" class="img_loader">
					<img src="{{asset('imagen/loader.gif')}}" alt="">
					<p> Cargando ...</p>
				</div>
			</div>

			<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
			
			<br>
			<a href="{{route('invenHerraInventarioCargas')}}" class="btn btn-success" ng-click="registrarInsumo()"><span class="glyphicon glyphicon-circle-arrow-down"></span> Listado de cargas</a>

			<center>
				<h3 class="text-success">Cargar de inventario</h3>
			</center>
			<br><br>

			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="input-group">

			      			<ui-select ng-model="insumoSelect.selected"
						             ng-disabled="disabled"
						             reset-search-input="true">
						    <ui-select-match placeholder="Ingrese descripción o codigo de un insumo">
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
			
			<div ng-show="thereInsumos()" >
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
					<button class="btn btn-success" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registar</button>
				</center>
			</div>
		</div>
	</div>

	<script type="text/ng-template" id="successRegister.html">
        <div class="modal-header">
            <h3 class="modal-title text-title-modal">{#response.menssage#}</h3>
        </div>
        <div class="modal-body">
        	<center>
        		<h3>Codigo unico de la carga de inventario</h3>
        		<h2><mark>{#response.codigo | codeforma#}</mark></h2>
        	</center>
        </div>
        <div class="modal-footer">
        	<center>
            	<button class="btn btn-success" type="button" ng-click="ok()"><span class="glyphicon glyphicon-ok-sign">
            	</span> OK</button>
           	</center>
        </div>
    </script>

    <script type="text/ng-template" id="confirmeRegister.html">
        <div class="modal-header">
            <h3 class="modal-title text-title-modal">
            	<span class="glyphicon glyphicon-circle-arrow-down"></span> Registrar carga de inventario
            </h3>
        </div>
        <div class="modal-body">
        	<center>
        		<h3 class="text-title-modal">Confirme el regitro para esta carga</h3>
        	</center>
        </div>
        <div class="modal-footer">
        	<center>
            	<button class="btn btn-success" ng-click="cofirme()"><span class="glyphicon glyphicon-ok-sign">
            	</span> Si</button>
            	<button class="btn btn-warning" ng-click="cancel()"><span class="glyphicon glyphicon-remove-sign"></span> No</button>
           	</center>
        </div>
    </script>

@endsection
