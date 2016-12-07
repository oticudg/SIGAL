<div class="modal-header">
	<h3 class="modal-title text-title-modal">
		<span class="glyphicon glyphicon-calendar text-primary"></span> Situar inventario
	</h3>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
		<alert ng-show="alert.type" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>
		</div>
		<div class="col-md-offset-2 col-md-8">
			<p class="input-group">
				<input type="text" id="fechaI" class="form-control text-center" datepicker-popup="yyyy-MM-dd" ng-model="fecha" is-open="openedI" close-text="Cerrar" current-text="Hoy" clear-text="Limpiar"/>
						<span class="input-group-btn">
							<button type="button" class="btn btn-primary text-white" ng-click="openI($event)"><i class="glyphicon glyphicon-calendar"></i></button>
						</span>
			</p>
		</div>
	</div>
</div>
<div class="modal-footer">
	<center>
		<button class="btn btn-primary" ng-click="buscar()"><span class="glyphicon glyphicon glyphicon-search"></span> Buscar</button>
		<button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
	</center>
</div>