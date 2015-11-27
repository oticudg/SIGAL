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
	@if( Auth::user()->haspermission('provedoreN') )
		<button class="btn btn-success" ng-click="registraProvedor()"><span class="glyphicon glyphicon-plus"></span> Nuevo provedor</button>
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
				<th class="col-md-2">Rif</th>
				<th>Nombre</th>
				@if( Auth::user()->haspermission('provedoreD') && Auth::user()->haspermission('provedoreM'))
					<th colspan="2" class="table-edit">Editar</th>		
				@elseif( Auth::user()->haspermission('provedoreD') || Auth::user()->haspermission('provedoreM') )
					<th class="table-edit">Editar</th>		
				@endif
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="provedor in provedores | filter:busqueda | itemsPerPage:cRegistro">
				<td>{#provedor.rif | capitalize#}</td>
				<td>{#provedor.nombre | capitalize#}</td>
				@if( Auth::user()->haspermission('provedoreM') )
					<td class="table-edit"><button class="btn btn-warning" ng-click="editarProvedor(provedor.id)"><span class="glyphicon glyphicon-pencil"></span> Editar</button></td>
				@endif
				@if( Auth::user()->haspermission('provedoreD') )
					<td class="table-edit"><button class="btn btn-danger"  ng-click="elimProvedor(provedor.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
				@endif
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>


@endsection

