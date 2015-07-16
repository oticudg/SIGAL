<div class="modal-header">
    <h3 style="color:#C93A36;" class="modal-title">Eliminar Presentación</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>

	<center><h3 class="text-danger" ng-show="btnVisivilidad">Esta seguro que desea borrar esta presentación</h3></center>

</div>
<div class="modal-footer">
    <center>
    	<button class="btn btn-danger" ng-show="btnVisivilidad" ng-click="eliminar()">Eliminar</button>
    	<button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()">Cancelar</button>
   	    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()">Ok</button>	
	</center>
</div>