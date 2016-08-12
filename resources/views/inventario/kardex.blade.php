@extends('panel')
@section('bodytag', 'ng-controller="kardexController"')
@section('addstyle')
  <script>
      var insumoKardex = {
          id:{{$insumo}},
          dateI:"{{$dateI}}",
          dateF:"{{$dateF}}"
      };

  </script>
@endsection

@section('front-page')

  <div data-loading class="simgle_loader">
    <div id="img_loader" class="img_single_loader">
      <img src="{{asset('imagen/loader.gif')}}" alt="">
      <p> Cargando ...</p>
    </div>
  </div>

	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-th-list"></span> Inventario</li>
      <li><a href="{{route('invenInicio')}}"><span class="glyphicon glyphicon-equalizer"></span> Existencia</a></li>
			<li class="nav-active"><span class="glyphicon glyphicon-list-alt"></span> Kardex</a></li>
		</ul>
	</nav>

	<br>
	<br>

	<div class="row">
		<div class="col-md-1">
    	<label for="cantidad">Registros</label>
			<select id="cantidad" class="form-control" ng-model="cRegistro">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
		</div>

    <div class="col-md-offset-2 col-md-6" style="padding-top:2%; text-align:center;">
      <table class="table table-striped">
        <tbody>
          <tr>
            <td class="bg-success text-success">Desde</td>
            <td>{#insumoInfo.dateI#}</td>
            <td class="bg-success text-success">Hasta</td>
            <td>{#insumoInfo.dateF#}</td>
            <td class="bg-success text-success">Movimientos</td>
            <td>{#movimientos.length#}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-md-offset-1 col-md-2" style="padding-top:2%; text-align:right;">
      <button class="btn btn-success dropdown-toggle" data-toggle="dropdown" tooltip="Filtrar registros">
        <span class="glyphicon glyphicon-search"></span></button>

      <ul class="dropdown-menu pull-right" role="menu">
        <li ng-click="filterPanel()" ><a href="#">Filtros</a></li>
        <li ng-click="search()" ><a href="#">Busqueda avanzada</a></li>
      </ul>

      <button type="button" class="btn btn-success" ng-click="update()" tooltip="Actualizar registros">
        <span class="glyphicon glyphicon-repeat"></span></button>
      @if(Auth::user()->hasPermissions(['inventory_report']))
        <a class="btn btn-warning" href="/reportes/kardex?insumo={#insumoInfo.insumo#}&dateI={#insumoInfo.dateI#}&dateF={#insumoInfo.dateF#}" target="_blank" tooltip="Generar reporte">
          <span class="glyphicon glyphicon glyphicon-print"></span>
        </a>
      @endif
    </div>
	</div>

	<br>
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
				<th class="col-md-1">Mov.</th>
				<th class="col-md-1">Exist.</th>
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
					<input type="text" class="form-control" placeholder="Movim" ng-model="filtro.movido">
				</td>
        <td>
					<input type="text" class="form-control" placeholder="Exist" ng-model="filtro.existencia">
				</td>
        <td></td>
			</tr>
			<tr dir-paginate="movimiento in movimientos | filter:filtro | itemsPerPage:cRegistro" pagination-id="movimientos">
				<td>{#movimiento.fecha#}</td>
        <td><span class="text-enlace" tooltip="{#movimiento.concepto#}">{#movimiento.abreviatura#}</span></td>
				<td>{#movimiento.pod | uppercase #}</td>
        <td>{#movimiento.type | uppercase #}</td>
        <td>{#movimiento.movido#}</td>
				<td>{#movimiento.existencia#}</td>
        @if(Auth::user()->hasPermissions(['inventory_movements']))
          <td><button class="btn btn-warning btn-sm" ng-click="detallesNota(movimiento.type, movimiento.referencia, movimiento.i)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
        @endif
      </tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}" pagination-id="movimientos"></dir-pagination-controls>
      </div>
  </div>

@endsection
