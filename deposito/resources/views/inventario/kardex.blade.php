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
{{--
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
			</div>
		</div>
	</div>--}}


	<div class="row">
		<div class="col-md-1">
    	<label for="cantidad">Registros</label>
			<select id="cantidad" class="form-control" ng-model="cRegistro">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
		</div>

    <div class="col-md-offset-9 col-md-2" style="padding-top:2%; text-align:right;">
      <button class="btn btn-success dropdown-toggle" data-toggle="dropdown">
        <span class="glyphicon glyphicon-search"></span></button>

      <ul class="dropdown-menu pull-right" role="menu">
        <li ng-click="filterPanel()" ><a href="#">Filtros</a></li>
        <li ng-click="registrosInsumos('todos')" ><a href="#">Busqueda avanzada</a></li>
      </ul>

      <a class="btn btn-warning" href="#" target="_blank">
        <span class="glyphicon glyphicon glyphicon-print"></span>
      </a>
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
        <td>{{strtoupper($insumoData['codigo'])}}</td>
         <td>{{strtoupper($insumoData['descripcion'])}}</td>
      </tr>
    </tbody>
  </table>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="col-md-1">Fecha</th>
				<th>Procedencia o Destino</th>
        <th class="col-md-1">Tipo</th>
				<th class="col-md-1">Movim</th>
				<th class="col-md-1">Exist</th>
        <th class="col-md-1">Nota</th>
			</tr>
		</thead>
		<tbody>
      <tr ng-show="barSearch">
				<td>
					<input type="text" class="form-control" placeholder="Fecha" ng-model="search.fecha">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Procedencia o Destino" ng-model="search.pod">
				</td>
				<td>
					<input type="text" class="form-control" placeholder="Tipo" ng-model="search.type">
				</td>
        <td>
					<input type="text" class="form-control" placeholder="Movim" ng-model="search.movido">
				</td>
        <td>
					<input type="text" class="form-control" placeholder="Exist" ng-model="search.existencia">
				</td>
        <td></td>
			</tr>
			<tr dir-paginate="movimiento in movimientos | filter:search | itemsPerPage:cRegistro" pagination-id="movimientos">
				<td>{#movimiento.fecha#}</td>
				<td>{#movimiento.pod | uppercase #}</td>
        <td>{#movimiento.type | uppercase #}</td>
        <td>{#movimiento.movido#}</td>
				<td>{#movimiento.existencia#}</td>
        <td><button class="btn btn-warning btn-sm" ng-click="detallesNota(movimiento.type, movimiento.referencia, movimiento.i)"><span class="glyphicon glyphicon-eye-open"></span></button></td>
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}" pagination-id="movimientos"></dir-pagination-controls>
      </div>
  </div>

@endsection
