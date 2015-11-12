<div class="modal-header">
    <h3 style="color:#54AF54;" class="modal-title"><span class="glyphicon glyphicon-plus"></span> Nuevo Usuario</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>


	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Nombre" ng-model="data.nombre">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Apellido" ng-model="data.apellido">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Cedula" ng-model="data.cedula">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="text" placeholder="Email" ng-model="data.email">
		</div>
	</div>

	<br>
	

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="password" placeholder="Contraseña" ng-model="data.password">
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<input class="form-control" type="password" placeholder="Repetir contraseña" ng-model="data.password_confirmation">
		</div>
	</div>

	<br>

	<center><h3 class="text-title-modal">Permisos</h3></center>
	
	<br>

	<div class="row">
		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Usuarios</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-click="usuarioActive()" ng-model="data.pUsuario">Usuarios</label>
			<div ng-show="data.pUsuario">
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pUsuario" ng-model="data.pUsuarioR">Registrar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pUsuario" ng-model="data.pUsuarioM">Editar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pUsuario" ng-model="data.pUsuarioE">Eliminar</label>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Proveedores</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-click="provedorActive()" ng-model="data.pProvedor">Proveedores</label>
			<div ng-show="data.pProvedor">
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pProvedor" ng-model="data.pProvedorR">Registrar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pProvedor" ng-model="data.pProvedorM">Editar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pProvedor" ng-model="data.pProvedorE">Eliminar</label>
					</div>
				</div>
			</div>
		</div>

		
		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Departamentos</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-click="departamentoActive()" ng-model="data.pDepartamento">Departamentos</label>
			<div ng-show="data.pDepartamento">
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pDepartamento" ng-model="data.pDepartamentoR">Registrar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pDepartamento" ng-model="data.pDepartamentoE">Eliminar</label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<br>
	
	<div class="row">
		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Insumos</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-click="insumoActive()" ng-model="data.pInsumo">Insumos</label>
			<div ng-show="data.pInsumo">
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pInsumo" ng-model="data.pInsumoR">Registrar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pInsumo" ng-model="data.pInsumoM">Editar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pInsumo" ng-model="data.pInsumoE">Eliminar</label>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Inventario</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-click="inventarioActive()" ng-model="data.pInventario">Inventario</label>
			<div ng-show="data.pInventario">
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pInventario" ng-model="data.pInventarioH">Herramientas</label>
					</div>
				</div>
			</div>
		</div>

		
		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Modificaciones</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-model="data.pModificacion">Modificaciones</label>
		</div>
	</div>
	
	<br>
	
	<div class="row">
		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Entradas</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-click="entradaActive()" ng-model="data.pEntrada">Entradas</label>
			<div ng-show="data.pEntrada">
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pEntrada" ng-model="data.pEntradaR">Registrar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pEntrada" ng-model="data.pEntradaV">Auditar</label>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Salidas</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-model="data.pSalida">Salidas</label>
			<div ng-show="data.pSalida">
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pSalida" ng-model="data.pSalidaR">Registrar</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 col-md-offset-1">
						<label class="checkbox-inline"><input type="checkbox" ng-checked="data.pSalida" ng-model="data.pSalidaV">Auditar</label>
					</div>
				</div>
			</div>	
		</div>

		<div class="col-md-3 col-md-offset-1">
			<h4 class="text-muted">Estadisticas</h4>
			<label class="checkbox-inline"><input type="checkbox" ng-checked="todo" ng-model="data.pEstadistica">Estadisticas</label>
		</div>
	</div>

	<br>

</div>
<div class="modal-footer">
    <button class="btn btn-success" ng-show="btnVisivilidad" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>	
</div>