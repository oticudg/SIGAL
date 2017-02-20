@extends('base')
@section('bodytag', 'ng-controller="inventarioController"')

@section('panel-name', '<i class="glyphicon glyphicon-equalizer text-info"></i> Existencia')

@section('content')
	
	<div class="row">
		<div class="col-xs-12">
			<div class="box box-primary">
				<div class="box-header">
				</div>	
				<div class="box-body">
					<div class="dataTables_wrapper form-inline dt-bootstrap">
						<div class="row">
							<div class="col-sm-4">
								<div>
									<span>Mostrar</span>
									<select id="cantidad" class="form-control" ng-model="cRegistro" class="form-control input-sm">
										<option value="10">10</option>
										<option value="25">25</option>
										<option value="50">50</option>
										<option value="100">100</option>
									</select> 
								</div>
							</div>

							<div class="col-sm-4 text-center">
								<table class="table table-striped">
									<tbody>
										<tr>
											<td class="bg-info text-info">Fecha</td>
											<td>{#dateF#}</td>
											<td class="bg-info text-info">Insumos</td>
											<td>{#insumos.length#}</td>
										</tr>
									</tbody>
								</table>
							</div>

							<div class="col-sm-offset-1 col-sm-3 text-right">		
								@if(Auth::user()->hasPermissions(['inventory_report']))
										<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown" tooltip="Reportes" ng-hide="status">
										<span class="glyphicon glyphicon-list-alt"></span></button>

										<ul class="dropdown-menu" role="menu">
											<li ng-click="parcialInventario()"><a href="#"><i class="fa fa-square"></i> Inventario parcial</a></li>
											<li><a href="{{route('reporInv', ['report' => 'total'])}}?date={#dateF#}&filter=true" target="_blank"><i class="fa fa-check-square-o"></i> Inventario sin fallas</a></li>
								          	<li><a href="{{route('reporInv', ['report' => 'total'])}}?date={#dateF#}" target="_blank"><i class="fa fa-file-pdf-o"></i> Inventario total PDF</a></li>
								          	<li><a href="{{route('reporExcel')}}?date={#dateF#}"><i class="fa fa-file-excel-o"></i> Inventario total EXCEL</a></li>
										</ul>

										<span ng-show="status">
											<button ng-class="thereIsSelect() ? 'active':'disabled'"  tooltip="Generar reporte" ng-click="gerenarParcial()" class="btn btn-primary"><span class="glyphicon glyphicon-list-alt"></span></button>
											<button ng-click="closeSelect()" class="btn btn-danger" tooltip="Cancelar"><span class="glyphicon glyphicon-remove"></span></button>
										</span>
								@endif

								<span ng-hide="status">
						     	<button class="btn btn-primary" ng-click="search()" tooltip="Filtrar registros">
						        <span class="glyphicon glyphicon-search"></span></button>

									<button type="button" class="btn btn-primary" ng-click="dateSelect()" tooltip="Situar el inventario en una fecha">
						        <span class="glyphicon glyphicon-calendar"></span></button>

									<button type="button" class="btn btn-primary" ng-click="move()" tooltip="Insumos con movimientos"><span class="glyphicon glyphicon-transfer"></span></button>
									<button type="button" class="btn btn-primary" ng-click="current()" tooltip="Situar inventario en fecha actual"><span class="glyphicon glyphicon-screenshot"></span></button>
								</span>	
							</div>
						</div>
					</div>

					<br>
					
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th ng-show="status"class="col-md-1">
									<label class="checkbox-inline">
									<input type="checkbox" ng-checked="all" ng-model="all" ng-click="select()">
									Todos</label>
								</th>
								<th class="col-md-2"><i class="fa fa-barcode"></i> Código</th>
								<th class="col-md-2"><i class="fa fa-commenting"></i> Descripción</th>
								<th class="col-md-1"><i class="fa fa-sort-numeric-asc"></i> Promedio</th>
								<th class="col-md-1"><i class="glyphicon glyphicon-equalizer"></i> Existencia</th>
								@if(Auth::user()->hasPermissions(['inventory_kardex']))
									<th class="col-md-1"><i class="fa fa-book"></i> Kardex</th>
								@endif
							</tr>
						</thead>
						<tbody>
							<tr ng-show="barSearch">
								<td ng-show="status"></td>
								<td>
									<input type="text" class="form-control" placeholder="Código" ng-model="busqueda.codigo">
								</td>
								<td>
									<input type="text" class="form-control" placeholder="Descripción" ng-model="busqueda.descripcion">
								</td>
								<td>
									<input type="text" class="form-control text-right" placeholder="Promedio" ng-model="busqueda.promedio">
								</td>
								<td>
									<input type="text" class="form-control text-right" placeholder="Cantidad" ng-model="busqueda.existencia">
								</td>
				        		<td></td>
							</tr>
							<tr ng-click="selectInsumo(insumos.indexOf(insumo))" ng-class="insumo.color" dir-paginate="insumo in insumos | filter:busqueda | itemsPerPage:cRegistro">
								<td ng-show="status">
									<input type="checkbox" ng-checked="insumo.select">
								</td>
								<td>{#insumo.codigo#}</td>
								<td>{#insumo.descripcion#}</td>
								<td class="text-right">{#insumo.promedio#}</td>
								<td class="text-right"><a class="text-enlace" ng-click="LotesView(insumo.id)">{#insumo.existencia#}</a></td>
								@if(Auth::user()->hasPermissions(['inventory_kardex']))
								<td class="text-center"><a class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Ver Kardex" href="/inventario/kardex?insumo={#insumo.id#}&dateI={#dateI#}&dateF={#dateF#}" target="_blank">
										<span class="glyphicon glyphicon-eye-open"></span></a>
									</td>
								@endif
							</tr>
						</tbody>
					</table>

					<div>
				      <div class="text-center">
				     	 <dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{asset('/template/dirPagination.tpl.html')}}"></dir-pagination-controls>
				      </div>
				    </div>

					<script type="text/ng-template" id="date.html">
						@include('inventario.modals.date')
					</script>

					<script type="text/ng-template" id="lotes.html">
						@include('inventario.modals.lotes')	
					</script>
		
				</div>
			</div>
		</div>
	</div>

@endsection
