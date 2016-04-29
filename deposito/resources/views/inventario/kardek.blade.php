@extends('panel')
@section('bodytag', 'ng-controller="kardekController"')
@section('addstyle')
  <script>
    var insumo = {{$insumo}};
  </script>
@endsection

@section('front-page')

	<div data-loading class="div_loader">
		<div id="img_loader" class="img_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>

	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-th-list"></span> Inventario</li>
      <li><a href="{{route('invenInicio')}}"><span class="glyphicon glyphicon-equalizer"></span> Existencia</a></li>
			<li class="nav-active"><span class="glyphicon glyphicon-list-alt"></span> Kardek</a></li>
		</ul>
	</nav>

	<br>
	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-1">
    		<label for="cantidad">Registros</label>
			<select id="cantidad" class="form-control" ng-model="cRegistro">
				<option value="5">5</option>
				<option value="10">10</option>
				<option value="20">20</option>
			</select>
		</div>
	</div>

	<br>
	<br>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th ng-show="status"class="col-md-1">
					<label class="checkbox-inline">
					<input type="checkbox" ng-checked="all" ng-model="all" ng-click="select()">
					Todos</label>
				</th>
				<th class="col-md-1">Fecha</th>
				<th>Procedencia o Destino</th>
        <th class="col-md-1">Tipo</th>
				<th class="col-md-1">Movim</th>
				<th class="col-md-1">Exist</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="movimento in movimentos | filter:busqueda | itemsPerPage:cRegistro">
				<td ng-show="status">
					<input type="checkbox" ng-checked="movimento.select">
				</td>
				<td>{#movimento.fecha#}</td>
				<td>{#movimento.pod | uppercase #}</td>
        <td>{#movimento.type | uppercase #}</td>
        <td>{#movimento.movido#}</td>
				<td>{#movimento.existencia#}</td>
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
  </div>

@endsection
