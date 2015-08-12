@extends('panel')
@section('bodytag', 'ng-controller="provedoresController"')
@section('addscript')
<script src="{{asset('js/provedoresController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-folder-open"></span> Provedores
	</h5>
	
	<br>
			
	<button class="btn btn-success" ng-click="registraProvedor()"><span class="glyphicon glyphicon-plus"></span> Nuevo provedor</button>
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
				<th>Rif</th>
				<th>Nombre</th>
				<th>Telefono</th>
				<th>Contacto</th>
				<th>Direccion</th>
				<th>Gmail</th>
				<th colspan="2" class="table-edit">Editar</th>
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="provedor in provedores | filter:busqueda | itemsPerPage:5">
				<td>{#provedor.rif | capitalize#}</td>
				<td>{#provedor.nombre | capitalize#}</td>
				<td>{#provedor.telefono#}</td>
				<td>{#provedor.contacto | capitalize#}</td>
				<td class="col-md-2">{#provedor.direccion#}</td>
				<td>{#provedor.email#}</td>
				<td class="table-edit"><button class="btn btn-warning" ng-click="editarProvedor(provedor.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				<td class="table-edit"><button class="btn btn-danger"  ng-click="elimProvedor(provedor.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>


@endsection

