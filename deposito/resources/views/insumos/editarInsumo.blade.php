<div class="modal-header">
    <h3 style="color:#54AF54;" class="modal-title"><span class="glyphicon glyphicon-pencil"></span> Editar Insumo</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>
	
	<div class="row">
		<div class="col-md-4 col-md-offset-4">	
			<center>
				<h3>Codigo</h3>
				<h3><strong class="text-success">{#codigo#}</strong><h3>
			</center>
		</div>
	</div>

	<div class="row">
		<div class="col-md-offset-2 col-md-4">
			<input class="form-control" type="text" placeholder="Principio Activo" ng-model="principio_activo">
		</div>

		<div class="col-md-4">
			<input class="form-control" type="text" placeholder="Marca/Laboratorio" ng-model="marca">
		</div>
	</div>
	
	<br>

	<div class="row">
		<div class="col-md-4">
			<select class="form-control" ng-model="presentacion">
				<option value="">Presentación</option>
				<option value="{#presentacion.id#}" ng-repeat="presentacion in presentaciones">{#presentacion.nombre#}</option>
			</select>
		</div>
		
		<div class="col-md-4">
			<select class="form-control" ng-model="seccion">
				<option value="">Sección</option>
				<option value="{#seccion.id#}" ng-repeat="seccion in secciones">{#seccion.nombre#}</option>
			</select>
		</div>
		
		<div class="col-md-4">
			<select class="form-control" ng-model="medida">
				<option value="">Unidad de Medida</option>	
				<option value="{#unidadMedida.id#}" ng-repeat="unidadMedida in unidadMedidas">{#unidadMedida.nombre#}
			</select>
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-4">
			<input class="form-control" type="text" placeholder="Cantidad minima" ng-model="cantidadM">
		</div>

		<div class="col-md-4">
			<input class="form-control" type="text" placeholder="Cantidad maxima" ng-model="cantidadX">
		</div>

		<div class="col-md-4">
			<input class="form-control" type="text" placeholder="Ubicacion" ng-model="ubicacion">
		</div>
	</div>
	
	<br>

	<div class="row">
		<div class="col-md-4">
			<select class="form-control" ng-model="deposito">
				<option value="">Deposito</option>
				<option value="alimentacion">Alimentacion</option>
				<option value="farmacia">Farmacia</option>
			</select>
		</div>

		<div class="col-md-8">
			<textarea  class="form-control" placeholder="Descripcion" ng-model="descripcion" style="width:570px; max-width:570px;"></textarea>
		</div>
	</div>

	<br>
	
	<div class="row">
		<div class="col-md-4">
			<input class="form-control" type="file" ngf-select ng-model="file">
		</div>
		
		<div class="col-md-8">
			<center>
					<img ng-show="file[0] != null" ngf-src="file[0]" class="img-thumbnail img-lg">
					<img ng-hide="file[0]" src="files/insumos/{#imagen#}" class="img-thumbnail img-lg">
			</center>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button class="btn btn-success" ng-show="btnVisivilidad" ng-click="modificar()"><span class="glyphicon glyphicon-ok-sign"></span> modificar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>