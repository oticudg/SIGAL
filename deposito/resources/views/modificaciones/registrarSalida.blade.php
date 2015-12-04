<div class="modal-header">
	<h3 ng-hide="status" class="modal-title text-title-modal"><span class="glyphicon glyphicon-plus"></span> Nueva Modificación</h3>
    <h3 ng-show="status" class="modal-title text-title-modal">
    	<span class="glyphicon glyphicon-circle-arrow-down"></span> Pro-Forma de Salida: <strong>{#salida.codigo#}</strong>
    </h3>
</div>
<div class="modal-body">

	<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
	
	<div ng-hide="status">
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="input-group">
			  		<input type="text" class="form-control text-center" ng-model="codigo" placeholder="Codigo de Pro-Forma">
					<div class="input-group-btn">
					    <button class="btn btn-success" ng-click="ubicarSalida()"><span class="glyphicon glyphicon-search"></span></button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div ng-show="status">
	
		<table class="table table-bordered custon-table-bottom-off">
			<thead>
				<th>Servicio</th>
				<th>Modificar servicio</th>
			</thead>
			<tbody>
				<td class="col-md-6">{#salida.departamento#}</td>
				<td class="warning">
					<select class="form-control" ng-model="departamento">
						<option value=" " selected >Servicio</option>
						<option value="{#departamento.id#}" ng-repeat="departamento in departamentos">{#departamento.nombre#}</option>
					</select>
				</td>
			</tbody>
		</table>
		<table class="table table-striped custon-table-top-off">
			<thead>
				<tr>
					<th class="col-md-2">Codigo</th>
					<th class="col-md-4">Descripción</th>
					<th class="col-md-3" colspan="2">Solicitado <span class="glyphicon glyphicon-resize-horizontal"></span> Modificar</th>
					<th class="col-md-3 separ" colspan="2">Despachado <span class="glyphicon glyphicon-resize-horizontal"></span> Modificar</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-class="insumo.style" dir-paginate="insumo in insumos | itemsPerPage:5">
					<td class="col-md-2">{#insumo.codigo#}</td>
					<td>{#insumo.descripcion#}</td>
					<td class="col-md-1">{#insumo.solicitado#}</td>
					<td class="col-md-1 warning">
						<input class="form-control text-center" type="number" ng-model="insumo.Msolicitado">
					</td>
					<td class="col-md-1 separ">{#insumo.despachado#}</td>
					<td class="col-md-1 warning">
						<input class="form-control text-center" type="number" ng-model="insumo.Mdespachado">
					</td>
					
				</tr>
			</tbody>
		</table>
	</div>
	
	{{--Paginacion de la tabla de insumos--}}	
    <div class="text-center">
 	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
  	</div>

</div>
<div class="modal-footer">
<button class="btn btn-success" ng-show="btnVisivilidad && status" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>