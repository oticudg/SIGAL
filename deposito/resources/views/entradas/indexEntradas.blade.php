@extends('panel')
@section('bodytag', 'ng-controller="entradasController"')
@section('addscript')
<script src="{{asset('js/entradasController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-th"></span> Entradas
	</h5>
	<br>

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="input-group">
		  		<span class="input-group-addon btn-success text-white"><span class="glyphicon glyphicon-search"></span></span>
		  		<input type="text" class="form-control" ng-model="busqueda">
			</div>
		</div>
	</div>

	<br>
	<br>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th class="col-md-2">Fecha</th>
				<th class="col-md-2">Codigo</th>
				<th class="col-md-3">Departamento</th>
				<th class="col-md-3">Provedor</th>
				<th class="col-md-1">Detalles</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="entrada in entradas | filter:busqueda | itemsPerPage:2">
				<td>{#entrada.created_at#}</td>
				<td>{#entrada.codigo#}</td>
				<td>{#entrada.nombre#}</td>
				<td>{#provedore.nombre#}</td>
				<td><button class="btn btn-success" ng-click="elimInsumo(insumo.id)"><span class="glyphicon glyphicon-plus-sign"></span> Detalles</button></td>
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>

@endsection

