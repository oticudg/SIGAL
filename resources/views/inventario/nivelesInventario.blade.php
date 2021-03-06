@extends('base')
@section('bodytag', 'ng-controller="insumosAlertController"')

@section('panel-name', '<i class="fa fa-bell-o text-primary"></i> Insumos en alerta')

@section('content')
	
	<div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right ui-sortable-handle">
              <li class="active"><a href="#niveles" data-toggle="tab" ng-click="obtenerInsumos()"><i class="glyphicon glyphicon-tasks text-primary"></i> Niveles</a></li>
              <li><a href="#vencimiento" data-toggle="tab" ng-click="obtenerInsumosv()"><i class="glyphicon glyphicon-calendar text-primary"></i> Vencimiento</a></li>
            </ul>
            <div class="tab-content">
            	<div class="tab-pane active" id="niveles">

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
									<th class="col-md-2"><i class="fa fa-barcode"></i> Código</th>
									<th class="col-md-4"><i class="fa fa-commenting"></i> Descripción</th>
									<th class="col-md-1"><i class="glyphicon glyphicon-equalizer"></i> Existencia</th>
									<th class="col-md-1"><i class="fa fa-cart-arrow-down"></i> Nivel crítico</th>
									<th class="col-md-1"><i class="fa fa-caret-square-o-down"></i> Nivel bajo</th>
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

            	<div class="tab-pane" id="vencimiento">
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
									<th class="col-md-2"><i class="fa fa-barcode"></i> Código</th>
									<th class="col-md-3"><i class="fa fa-commenting"></i> Descripción</th>
									<th class="col-md-1"><i class="fa fa-cubes"></i> Lote</th>
									<th class="col-md-1"><i class="glyphicon glyphicon-calendar"></i> Fecha</th>
									<th class="col-md-1"><i class="fa fa-list-ol"></i> Cantidad</th>
								</tr>
							</thead>
							<tbody>
								<tr class="{#insumo.type#}" dir-paginate="insumo in insumosv | filter:busqueda | itemsPerPage:cRegistro" pagination-id="vencimiento">
									<td>{#insumo.codigo#}</td>
									<td>{#insumo.descripcion#}</td>
									<td class="text-center">{#insumo.lote#}</td>
									<td>{#insumo.fecha#}</td>
									<td class="text-right">{#insumo.cantidad#}</td>
								</tr>
							</tbody>
						</table>

						<div>
					      <div class="text-center">
					     	 <dir-pagination-controls boundary-links="true" pagination-id="vencimiento" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
					      </div>
					    </div>	
					</div>
            	</div>
          	</div>
    </div>
    
@endsection

