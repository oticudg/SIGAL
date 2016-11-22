@extends('base')
@section('bodytag', 'ng-controller="insumosAlertController"')

@section('panel-name', 'Insumos en alerta')

@section('breadcrumb')
	<li><a href="#"><i class="fa fa-dashboard"></i>Exitencias</a></li>
	<li class="active">Salidas</li>
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
							<div class="col-sm-6">
								<div class="dataTables_length">
									<span>Mostrar</span>
									<select id="cantidad" class="form-control" ng-model="cRegistro" class="form-control input-sm">
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select> 
								</div>
							</div>

							<div class="col-sm-6 text-right">		
							  	<input type="text" class="form-control" ng-model="busqueda" placeholder="Buscar..">
							</div>
						</div>

						<br>

						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="col-md-2">Codigo</th>
									<th>Descripcion</th>
									<th class="col-md-1 text-right">Existencia</th>
									<th class="col-md-1 text-right">Nivel Critico</th>
									<th class="col-md-1 text-right">Nivel Bajo</th>
								</tr>
							</thead>
							<tbody>
								<tr ng-class="calculaEstatus(insumo.min, insumo.med, insumo.existencia)" dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro">
									<td>{#insumo.codigo#}</td>
									<td>{#insumo.descripcion#}</td>
									<td class="text-right">{#insumo.existencia#}</td>
									<td class="text-right">{#insumo.min#}</td>
									<td class="text-right">{#insumo.med#}</td>
								</tr>
							</tbody>
						</table>

						<div>
					      <div class="text-center">
					     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
					      </div>
					    </div>	
					</div>
				</div>
			</div>
		</div>
	</div>		

@endsection

