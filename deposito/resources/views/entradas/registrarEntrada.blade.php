@extends('panel')
@section('bodytag', 'ng-controller="registroEntradaController"')
@section('addscript')
<script src="{{asset('js/registroEntradaController.js')}}"></script>
@endsection

@section('front-page')
	<div ng-show="loader" class="div_loader">
		<div id="img_loader" class="img_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>

	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-transfer"></span> Tranferencias</li>	
			<li class="nav-active"><span class="glyphicon glyphicon-circle-arrow-down"></span> Registro de Entrada</li>
		</ul>
	</nav>


	<center>
		<h3 class="text-title-modal">Registro de Pro-Forma de entrada</h3>
	</center>
	
	<br>

	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
	
	<ul class="nav nav-tabs">
		<li class="active" ng-click="restablecer()"><a data-toggle="tab" class="text-enlace" href="#orden">Orden</a></li>
		<li ng-click="restablecer()"><a data-toggle="tab" class="text-enlace" href="#donacion">Donacion</a></li>
		<li ng-click="restablecer()"><a data-toggle="tab" class="text-enlace" href="#devolucion">Devolucion</a></li>
	</ul>
	
	{{--Panel de registro de ordenes de compra--}}
	<div class="tab-content">
		<div id="orden" class="tab-pane fade in active">
			
			<br>

			<div class="row">
				<div class="col-md-2">
					<input class="form-control text-center" type="text" placeholder="N° Orden" ng-model="orden">
				</div>

				<div class="form-group col-md-3 text-title-modal">
						<select class="form-control" id="provedor" ng-model="provedor">
							<option value="" selected disabled>Proveedor</option>
							<option value="{#provedore.id#}" ng-repeat="provedore in provedores">{#provedore.nombre#}</option>
						</select>
				</div>
			</div>

			<br>

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
							<th class="col-md-1">Lote</th>
							<th class="col-md-2">Fecha de vencimiento</th>
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
							<td>
								<input class="form-control text-center" type="text" ng-model="insumo.lote">
							</td>
							<td>
								<p class="input-group">
					              <input type="text" id="dateI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="insumo.fecha" is-open="insumo.dI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
					              <span class="input-group-btn">
					                <button type="button" class="btn btn-success text-white" ng-click="openI($event, insumos.indexOf(insumo))"><i class="glyphicon glyphicon-calendar"></i></button>
					              </span>
				        		</p>
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
					<button class="btn btn-success" ng-click="registrar('orden')"><span class="glyphicon glyphicon-ok-sign"></span> Registar</button>
				</center>
			</div>
		</div>
		
	    {{--Panel de registro de donaciones--}}
		<div id="donacion" class="tab-pane fade">

			<br>

			<div class="row">
				<div class="form-group col-md-3 text-title-modal">
						<select class="form-control" id="provedor" ng-model="provedor">
							<option value="" selected disabled>Proveedor</option>
							<option value="{#provedore.id#}" ng-repeat="provedore in provedores">{#provedore.nombre#}</option>
						</select>
				</div>
			</div>

			<br>

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
			
			<div ng-show="thereInsumos(insumosDon)" >
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Codigo</th>
							<th>Descripción</th>
							<th>Cantidad</th>
							<th class="col-md-1">Lote</th>
							<th class="col-md-2">Fecha de vencimiento</th>
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
							<td>
								<input class="form-control text-center" type="text" ng-model="insumo.lote">
							</td>
							<td>
								<p class="input-group">
					              <input type="text" id="dateI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="insumo.fecha" is-open="insumo.dI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
					              <span class="input-group-btn">
					                <button type="button" class="btn btn-success text-white" ng-click="openI($event, insumos.indexOf(insumo))"><i class="glyphicon glyphicon-calendar"></i></button>
					              </span>
				        		</p>
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
					<button class="btn btn-success" ng-click="registrar('donacion')"><span class="glyphicon glyphicon-ok-sign"></span> Registar</button>
				</center>
			</div>
			
		</div>

		{{--Panel de registro de devoluciones--}}
		<div id="devolucion" class="tab-pane fade">

			<br>

			<div class="row">
				<div class="form-group col-md-3 text-title-modal">
						<select class="form-control" id="departamento" ng-model="departamento">
							<option value="" selected disabled>Servicio</option>
							<option value="{#departamento.id#}" ng-repeat="departamento in departamentos">{#departamento.nombre#}</option>
						</select>
				</div>
			</div>

			<br>

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
			
			<div ng-show="thereInsumos(insumosDon)" >
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Codigo</th>
							<th>Descripción</th>
							<th>Cantidad</th>
							<th class="col-md-1">Lote</th>
							<th class="col-md-2">Fecha de vencimiento</th>
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
							<td>
								<input class="form-control text-center" type="text" ng-model="insumo.lote">
							</td>
							<td>
								<p class="input-group">
					              <input type="text" id="dateI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="insumo.fecha" is-open="insumo.dI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
					              <span class="input-group-btn">
					                <button type="button" class="btn btn-success text-white" ng-click="openI($event, insumos.indexOf(insumo))"><i class="glyphicon glyphicon-calendar"></i></button>
					              </span>
				        		</p>
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
					<button class="btn btn-success" ng-click="registrar('devolucion')"><span class="glyphicon glyphicon-ok-sign"></span> Registar</button>
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
        		<h3>Codigo unico de la Entrada</h3>
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
            	<span class="glyphicon glyphicon-circle-arrow-down"></span> Registrar entrada
            </h3>
        </div>
        <div class="modal-body">
        	<center>
        		<h3 class="text-title-modal">Confirme el regitro para esta entrada</h3>
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
