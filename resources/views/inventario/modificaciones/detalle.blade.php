<div class="modal-header">
  <div class="row">
    <div class="col-md-6">
	     <h3 class="modal-title text-title-modal">
	    	   <span class="glyphicon glyphicon-edit"></span> Modificación
	     </h3>
		</div>
	</div>
</div>
<div class="modal-body">

  <div class="row">
    <div class="col-md-6">
      <div class="panel panel-default">
          <div class="panel-heading">Concepto</div>
          <div class="panel-body">
            <table class="table table-bordered custon-table-bottom-off">
              <tbody>
                <tr>
                  <th>Original</th>
                </tr>
                <tr>
                  <td>{#modificacion.original_documento#}</td>
                </tr>
              </tbody>
            </table>
            <table class="table table-bordered tableWarning custon-table-top-off" ng-show="modificacion.updated_documento">
              <tbody>
                <tr>
                  <th>Modificación</th>
                </tr>
                <tr>
                  <td>{#modificacion.updated_documento#}</td>
                </tr>
              </tbody>
            </table>
          </div>
       </div>
    </div>

    <div class="col-md-6">
      <div class="panel panel-default">
          <div class="panel-heading">Tercero</div>
          <div class="panel-body">
            <table class="table table-bordered custon-table-bottom-off">
              <tbody>
                <tr>
                  <th>Original</th>
                </tr>
                <tr>
                  <td>{#modificacion.original_tercero#}</td>
                </tr>
              </tbody>
            </table>
            <table class="table table-bordered tableWarning custon-table-top-off" ng-show="modificacion.updated_tercero">
              <tbody>
                <tr>
                  <th>Modificación</th>
                </tr>
                <tr>
                  <td>{#modificacion.updated_tercero#}</td>
                </tr>
              </tbody>
            </table>
          </div>
       </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
          <div class="panel-heading">Movimiento</div>
          <div class="panel-body">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class="col-md-1">Fecha</th>
                  <th class="col-md-1">Codigo</th>
                  <th class="col-md-1">Concepto</th>
                  <th class="col-md-1">Tipo</th>
                  <th class="col-md-5">Tercero</th>
                  @if( Auth::user()->hasPermissions(['inventory_movements']))
                    <th class="col-md-1">Detalles</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>{#movimiento.fecha#}</td>
                  <td>{#movimiento.codigo | codeforma#}</td>
                  <td><span class="text-enlace" tooltip="{#movimiento.concepto#}">{#movimiento.abreviatura#}</span></td>
                  <td>{#movimiento.type#}</td>
                  <td>{#movimiento.tercero#}</td>
                  @if( Auth::user()->hasPermissions(['inventory_movements']))
                    <td><button class="btn btn-warning" ng-click="detallesNota(movimiento.type,movimiento.id)"><span class="glyphicon glyphicon-plus-sign"></span></button></td>
                  @endif
                </tr>
              </tbody>
            </table>
          </div>
       </div>
    </div>
  </div>

</div>
<div class="modal-footer">
    <button class="btn btn-success"  ng-show="uiStatus" ng-click="update()"><span class="glyphicon glyphicon-ok-sign"></span> Modificar</button>
    <button class="btn btn-warning" ng-click="cancelar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
</div>
