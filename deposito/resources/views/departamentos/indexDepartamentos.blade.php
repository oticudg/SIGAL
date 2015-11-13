@extends('panel')
@section('bodytag', 'ng-controller="departamentosController"')
@section('addscript')
<script src="{{asset('js/departamentosController.js')}}"></script>
@endsection

@section('front-page')

	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-briefcase"></span> Departamentos
	</h5>
	<br>
	
	@if( Auth::user()->haspermission('departamentoN') )		
		<a href="/registrarDepartamento"><button class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Nuevo Departamento</button></a>
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
				<th>Nombre</th>
				<th class="col-md-1">Sello</th>
				<th class="col-md-1">Firma</th>
				@if( Auth::user()->haspermission('departamentoD') )
					<th class="table-edit">Editar</th>		
				@endif
			</tr>
		</thead>
		<tbody>
			<tr dir-paginate="departamento in departamentos | filter:busqueda | itemsPerPage:cRegistro">
				<td>{#departamento.nombre | capitalize#}</td>
				<td><button class="btn btn-warning" ng-click="openImagen('/files/sellos/' + departamento.sello)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
				<td><button class="btn btn-warning" ng-click="openImagen('/files/firmas/' + departamento.firma)"><span class="glyphicon glyphicon-plus-sign"></span> Ver</button></td>
				@if( Auth::user()->haspermission('departamentoD') )
					<td class="table-edit"><button class="btn btn-danger" ng-click="eliminarDepartamento(departamento.id)"><span class="glyphicon glyphicon-remove"></span> Eliminar</button></td>
				@endif
			</tr>
		</tbody>
	</table>

	<div>
      <div class="text-center">
     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
      </div>
    </div>

    <script type="text/ng-template" id="imagen.html">
        <div class="modal-header">
        </div>
        <div class="modal-body">
        	<center><img src="{#imagen#}" class="img-thumbnail"></center>
        </div>
        <div class="modal-footer">
            	<button class="btn btn-warning" type="button" ng-click="cerrar()"><span class="glyphicon glyphicon-remove-sign"></span> Cerrar</button>
        </div>
    </script>

@endsection

