@extends('panel')
@section('bodytag', 'ng-controller="seccionesController"')
@section('addscript')
<script src="{{asset('js/seccionesController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-tags"></span> Secciones
	</h5>

	<br>
			
	<button class="btn btn-success" ng-click="registrarSeccion()"><span class="glyphicon glyphicon-plus"></span> Nueva Sección</button>
	
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

	<br>
	<br>

	<table class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Nombre</th>
				<th colspan="2" class="table-edit">Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="seccion in secciones | filter:busqueda | itemsPerPage:5 ">
				<td>{#seccion.nombre#}</td>
				<td class="table-edit"><button class="btn btn-warning" ng-click="editarSeccion(seccion.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				<td class="table-edit"><button class="btn btn-danger"  ng-click="eliminarSeccion(seccion.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>


@endsection

