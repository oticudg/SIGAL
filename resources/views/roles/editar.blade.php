<div class="modal-header">
    <h3 class="modal-title text-title-modal"><span class="glyphicon glyphicon-pencil"></span> Editar rol</h3>
</div>
<div class="modal-body">

	<alert ng-show="alert" type="{#alert.type#}" close="closeAlert()">{#alert.msg#}</alert>

	<div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label  class="text-muted" for="nombre">Nombre</label>
        <input class="form-control" id="nombre" type="text" placeholder="Nombre" ng-model="data.nombre">
      </div>
    </div>
    <div class="col-md-8">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">Permisos</div>
          <div class="panel-body">
              <div class="row">
                <div class="col-md-4">
                  <div class="col-md-5">
                    <h4 class="text-muted">Usuarios</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'usuarios'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
                    <h4 class="text-muted">Proveedores</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'proveedores'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
                    <h4 class="text-muted">Departamentos</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'departamentos'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
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
                    <h4 class="text-muted">Insumos</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'insumos'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
                    <h4 class="text-muted">Invetario</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'inventario'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
                    <h4 class="text-muted">Modificaciones</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'modificaciones'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
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
                    <h4 class="text-muted">Movimientos</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'movimientos'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
                    <h4 class="text-muted">Almacenes</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'almacenes'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-4">
                  <div class="col-md-5">
                    <h4 class="text-muted">Documentos</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'documentos'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
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
                    <h4 class="text-muted">Roles</h4>
                    <div>
                      <div class="row" ng-repeat="permiso in permisos | filter:{modulo:'roles'}">
                        <div class="col-md-4">
                          <label class="checkbox-inline"><input  ng-click="assignPermission(permiso.id)" ng-checked="isAsignedPermission(permiso.id)" type="checkbox">{#permiso.nombre#}</label>
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
    <button class="btn btn-success" ng-show="btnVisivilidad" ng-click="registrar()"><span class="glyphicon glyphicon-ok-sign"></span> Modificar</button>
    <button class="btn btn-warning" ng-show="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cancelar</button>
    <button class="btn btn-success" ng-hide="btnVisivilidad" ng-click="cancelar()"><span class="glyphicon glyphicon-ok-sign"></span> Ok</button>
</div>
