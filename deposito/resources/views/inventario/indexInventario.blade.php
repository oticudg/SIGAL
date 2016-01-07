@extends('panel')
@section('bodytag', 'ng-controller="inventarioController"')
@section('addscript')
<script src="{{asset('js/inventarioController.js')}}"></script>
@endsection

@section('front-page')

	<nav class="nav-ubication">
		<ul class="nav-enlaces">
			<li><span class="glyphicon glyphicon-cog"></span> Administración</li>	
			<li class="nav-active"><span class="glyphicon glyphicon-th-list"></span> Inventario</li>
		</ul>
	</nav>

	<br>
	@if( Auth::user()->haspermission('inventarioH') )
		<a class="btn btn-success" href="{{route('invenHerraInicio')}}"><span class="glyphicon glyphicon-wrench"></span> Herramientas</a>
	@endif
	<br>
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
				<th class="col-md-2">Codigo</th>
				<th>Descripción</th>
				<th class="col-md-2">Existencia en Unidades</th>
			</tr>
		</thead>
		<tbody>
			<tr ng-class="calculaEstatus(insumo.min, insumo.med, insumo.existencia)" dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro">
				<td>{#insumo.codigo#}</td>
				<td>{#insumo.descripcion#}</td>
				<td>{#insumo.existencia#}</td>
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>

@endsection

