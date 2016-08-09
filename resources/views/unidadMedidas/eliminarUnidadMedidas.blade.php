<div class="modal-header">
    <h3 style="color:#C93A36;" class="modal-title"><span class="glyphicon glyphicon-remove"></span> Eliminar unidad de medida</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>

	<center><h3 class="text-danger" ng-show="btnVisivilidad">Esta seguro que desea borrar esta unidad de medida</h3></center>

</div>
<div class="modal-footer">
    <center>
    	<button class="btn btn-danger" ng-show="btnVisivilidad" ng-click="eliminar()"><span class="glyphicon glyphicon-ban-circle"></span>  Eliminar</button>
    	<button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
   	    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>	
	</center>
</div>