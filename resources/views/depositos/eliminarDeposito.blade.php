<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-trash text-danger"></span> Eliminar almacén</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>

	<center><h3 ng-show="btnVisivilidad">Esta seguro que desea eliminar este almacén <i class="fa fa-question-circle text-danger" aria-hidden="true"></i></h3></center>

</div>
<div class="modal-footer">
    <center>
    	<button class="btn btn-danger" ng-show="btnVisivilidad" ng-click="eliminar()"><span class="glyphicon glyphicon-trash"></span>  Eliminar</button>
    	<button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
   	    <button class="btn btn-primary" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>	
	</center>
</div>