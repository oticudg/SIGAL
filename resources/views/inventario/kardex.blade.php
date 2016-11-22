@extends('base')
@section('bodytag', 'ng-controller="kardexController"')
@section('addscript')
  <script>
      var insumoKardex = {
          id:{{$insumo}},
          dateI:"{{$dateI}}",
          dateF:"{{$dateF}}"
      };

  </script>
@endsection

@section('panel-name', 'Kardex')

@section('breadcrumb')
  <li><a href="#">Inventario</a></li>
  <li><a href="{{route('invenInicio')}}"><i class="fa fa-dashboard"></i>Exitencias</a></li>
  <li class="active">Kardex</li>
@endsection

@section('content')

  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header">
        </div>  
        <div class="box-body">
          <div class="dataTables_wrapper form-inline dt-bootstrap">
            <div class="row">
              <div class="col-sm-4">
                <div>
                  <span>Mostrar</span>
                  <select id="cantidad" class="form-control" ng-model="cRegistro" class="form-control input-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                  </select> 
                </div>
              </div>

              <div class="col-sm-4 text-center">
                <table class="table table-striped">
                  <tbody>
                    <tr>
                      <td class="bg-info text-info">Desde</td>
                      <td>{#insumoInfo.dateI#}</td>
                      <td class="bg-info text-info">Hasta</td>
                      <td>{#insumoInfo.dateF#}</td>
                      <td class="bg-info text-info">Movimientos</td>
                      <td>{#movimientos.length#}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="col-sm-offset-1 col-sm-3 text-right">   
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" tooltip="Filtrar registros">
                  <span class="glyphicon glyphicon-search"></span></button>

                <ul class="dropdown-menu pull-right" role="menu">
                  <li ng-click="filterPanel()" ><a href="#">Filtros</a></li>
                  <li ng-click="search()" ><a href="#">Busqueda avanzada</a></li>
                </ul>

                <button type="button" class="btn btn-primary" ng-click="update()" tooltip="Actualizar registros">
                  <span class="glyphicon glyphicon-repeat"></span></button>
                @if(Auth::user()->hasPermissions(['inventory_report']))
                  <a class="btn btn-warning" href="/reportes/kardex?insumo={#insumoInfo.insumo#}&dateI={#insumoInfo.dateI#}&dateF={#insumoInfo.dateF#}" target="_blank" tooltip="Generar reporte">
                    <span class="glyphicon glyphicon glyphicon-print"></span>
                  </a>
                @endif
              </div>
            </div>
          </div>

          <br>

          <table class="table table-bordered custon-table-bottom-off">
            <thead>
              <tr>
                <th class="col-md-2">Codigo</th>
                <th>Descripción</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{strtoupper($insumoData->codigo)}}</td>
                 <td>{{strtoupper($insumoData->descripcion)}}</td>
              </tr>
            </tbody>
          </table>

          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th class="col-md-1">Fecha</th>
                <th class="col-md-1">Concepto</th>
                <th>Procedencia o Destino</th>
                <th class="col-md-1">Tipo</th>
                <th class="col-md-1 text-right">Moviminto</th>
                <th class="col-md-1 text-right">Existencia</th>
                @if(Auth::user()->hasPermissions(['inventory_movements']))
                  <th class="col-md-1">Nota</th>
                @endif
              </tr>
            </thead>
            <tbody>
              <tr ng-show="barSearch">
                <td>
                  <input type="text" class="form-control" placeholder="Fecha" ng-model="filtro.fecha">
                </td>
                <td>
                  <input type="text" class="form-control" placeholder="Concepto" ng-model="filtro.abreviatura">
                </td>
                <td>
                  <input type="text" class="form-control" placeholder="Procedencia o Destino" ng-model="filtro.pod">
                </td>
                <td>
                  <input type="text" class="form-control" placeholder="Tipo" ng-model="filtro.type">
                </td>
                <td>
                  <input type="text" class="form-control text-right" placeholder="Movim" ng-model="filtro.movido">
                </td>
                <td>
                  <input type="text" class="form-control text-right" placeholder="Exist" ng-model="filtro.existencia">
                </td>
                <td></td>
              </tr>
              <tr dir-paginate="movimiento in movimientos | filter:filtro | itemsPerPage:cRegistro" pagination-id="movimientos">
                <td>{#movimiento.fecha#}</td>
                <td><span class="text-enlace" tooltip="{#movimiento.concepto#}">{#movimiento.abreviatura#}</span></td>
                <td>{#movimiento.pod | uppercase #}</td>
                <td>{#movimiento.type | uppercase #}</td>
                <td class="text-right">{#movimiento.movido#}</td>
                <td class="text-right">{#movimiento.existencia#}</td>
                @if(Auth::user()->hasPermissions(['inventory_movements']))
                  <td class="text-center"><button class="btn btn-warning" ng-click="detallesNota(movimiento.type, movimiento.referencia, movimiento.i)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
                @endif
              </tr>
            </tbody>
          </table>

          <div>
              <div class="text-center">
               <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}" pagination-id="movimientos"></dir-pagination-controls>
              </div>
          </div>
    
        </div>
      </div>
    </div>
  </div>

@endsection
