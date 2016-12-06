@extends('base')
@section('bodytag', 'ng-controller="registroSalidaController"')

@section('panel-name', 'Registro de Pro-Forma de Pedido')

@section('content')
	
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">					
				</div>	
				<div class="box-body">
					<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
					<div class="row">						
						<div class="col-sm-4">
							<ui-select ng-model="documentoSelect.selected"
				               ng-disabled="disabled"
				               reset-search-input="true" on-select="searchTerceros()">
					      <ui-select-match placeholder="Seleccione un concepto de salida">
					      {#$select.selected.nombre#}</ui-select-match>
					      <ui-select-choices repeat="documento in documentos | filter:$select.search track by documento.id">
					        <div ng-bind-html="documento.nombre | highlight: $select.search"></div>
					      </ui-select-choices>
					      </ui-select>
						</div>

						<div class="form-group col-sm-3" ng-show="panelTerceros">
					        <ui-select ng-model="terceroSelect.selected"
					                 ng-disabled="disabled"
					                 reset-search-input="true">
					        <ui-select-match placeholder="Seleccione un tercero">
					        {#$select.selected.nombre#}</ui-select-match>
					        <ui-select-choices repeat="tercero in terceros | filter:$select.search">
					          <div ng-bind-html="tercero.nombre | highlight: $select.search"></div>
					        </ui-select-choices>
					        </ui-select>
				   		</div>
						<div class="col-sm-5">
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

					<div ng-show="thereInsumos(insumosDon)">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Codigo</th>
									<th>Descripción</th>
									<th>lote</th>
									<th class="text-right">Solicitado</th>
									<th class="text-right">Despachado</th>
									<th>Eliminar</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-class="insumo.style" ng-repeat="insumo in insumos">
									<td class="col-md-2">{#insumo.codigo#}</td>
									<td>{#insumo.descripcion#}</td>
									<td class="col-md-1">
										<input class="form-control" type="text" ng-model="insumo.lote">
									</td>
									<td class="col-md-1">
										<input class="form-control text-right" type="number" ng-model="insumo.solicitado">
									</td>
									<td class="col-md-1">
										<input class="form-control text-right" type="number" ng-model="insumo.despachado">
									</td>
									<td class="col-md-1 text-center">
										<button class="btn btn-danger" ng-click="eliminarInsumo(insumos.indexOf(insumo))"><span class="glyphicon glyphicon-remove"></span>
										</button>
									</td>
								</tr>
							</tbody>
						</table>

						<br>

						<div class="text-right">
							<button class="btn btn-primary" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
						</div>
					</div>

					<script type="text/ng-template" id="successRegister.html">
				        <div class="modal-header">
				            <h3 class="modal-title text-title-modal">{#response.menssage#}</h3>
				        </div>
				        <div class="modal-body">
				        	<center>
				        		<h3>Codigo unico de la salida</h3>
				        		<h2><mark>{#response.codigo | codeforma#}</mark></h2>
				        	</center>
				        </div>
				        <div class="modal-footer">
				        	<center>
				            	<button class="btn btn-primary" type="button" ng-click="ok()"><span class="glyphicon glyphicon-ok-sign">
				            	</span> OK</button>
				           	</center>
				        </div>
				    </script>

				    <script type="text/ng-template" id="confirmeRegister.html">
				        <div class="modal-header">
				            <h3 class="modal-title text-title-modal">
				            	<span class="glyphicon glyphicon-circle-arrow-up text-primary"></span> Registrar salida
				            </h3>
				        </div>
				        <div class="modal-body">
				        	<center>
				        		<h3 class="text-title-modal">Confirme el registro para esta salida</h3>
				        	</center>
				        </div>
				        <div class="modal-footer">
				        	<center>
				            	<button class="btn btn-primary" ng-click="cofirme()"><span class="glyphicon glyphicon-ok-sign">
				            	</span> Si</button>
				            	<button class="btn btn-warning" ng-click="cancel()"><span class="glyphicon glyphicon-remove-sign"></span> No</button>
				           	</center>
				        </div>
				    </script>
			    </div>
		    </div>
		</div>
	</div>
@endsection
