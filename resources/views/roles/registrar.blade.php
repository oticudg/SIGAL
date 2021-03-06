<div class="modal-header">
    <h3 class="modal-title"><span class="glyphicon glyphicon-plus text-primary"></span> Nuevo rol</h3>
</div>
<div class="modal-body">

	<alert ng-repeat="alert in alerts" type="{#alert.type#}" close="closeAlert($index)">{#alert.msg#}</alert>

	<div class="row">
    <div class="col-md-4">
      <div class="form-group">
		  <label  class="text-muted" for="nombre"><i class="glyphicon glyphicon-compressed"></i> Nombre</label>
        <input class="form-control" id="nombre" type="text" placeholder="Nombre" ng-model="nombre">
      </div>
    </div>
    <div class="col-md-8">
      <div class="col-md-12">
        <div class="panel panel-primary">
			<div class="panel-heading"><i class="fa fa-check-circle-o"></i> Permisos</div>
          <div class="panel-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="fa fa-users text-info"></i> Usuarios</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'usuarios'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input ng-click="assignPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="glyphicon glyphicon-folder-open text-info"></i> Proveedores</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'proveedores'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input ng-click="assignPermission(permiso.id)"  type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="glyphicon glyphicon-briefcase text-info"></i> Departamentos</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'departamentos'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)"  type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <br>

              <div class="row">
                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="glyphicon glyphicon-th text-info"></i> Insumos</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'insumos'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="glyphicon glyphicon-th-list text-info"></i> Inventarios</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'inventario'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="glyphicon glyphicon-transfer text-info"></i> Movimientos</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'movimientos'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <br>

              <div class="row">

                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="glyphicon glyphicon-inbox text-info"></i> Almacenes</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'almacenes'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input ng-click="assignPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="glyphicon glyphicon-folder-close text-info"></i> Documentos</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'documentos'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
					  <h4 class="text-muted"><i class="glyphicon glyphicon-compressed text-info"></i> Roles</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'roles'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-primary" ng-show="btnVisivilidad" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-primary" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>
