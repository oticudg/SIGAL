@extends('base')
@section('conten')

	<div id='loader' class="div_loader_resouerce">
		<div id="img_loader" class="img_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>

	<div id="wrapper">
        <div class="overlay"></div>

        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation" style="background-color: #505152">
            <ul class="nav sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                       Menú
                    </a>
                </li>

                <li>
                    <a href="/inicio">Inicio</a>
                </li>

								{{-- Menu de administracion --}}
                @if( Auth::user()->hasPermissions(['users_consult', 'stores_consult', 'documents_consult', 'roles_consult', 'departs_consult', 'providers_consult', 'items_consult']) )
	                <li class="dropdown droAdmin admi">
		                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Administración <span class="caret"></span></a>
		                <ul class="dropdown-menu  dropdown-panel" role="menu">
			                @if( Auth::user()->hasPermissions(['users_consult']))
		                       <li><a href="/usuarios"><span class="glyphicon glyphicon-user"></span> Usuario</a></li>
							        @endif

											@if( Auth::user()->hasPermissions(['stores_consult']) )
												<li><a href="{{route('depoInicio')}}"><span class="glyphicon glyphicon-inbox"></span> Almacenes</a></li></li>
											@endif

											@if(Auth::user()->hasPermissions(['documents_consult']))
												<li><a href="{{route('documIndex')}}"><span class="glyphicon glyphicon-folder-close"></span> Documentos</a></li></li>
											@endif

											@if(Auth::user()->hasPermissions(['roles_consult']))
												<li><a href="{{route('rolesIndex')}}"><span class="glyphicon glyphicon-compressed"></span> Roles</a></li></li>
											@endif

					            @if( Auth::user()->hasPermissions(['departs_consult']) )
								   	 	   <li><a href="/departamentos"><span class="glyphicon glyphicon-briefcase"></span> Departamentos</a></li>
							       	@endif

							        @if( Auth::user()->hasPermissions(['providers_consult']) )
							       	 	<li><a href="/proveedores"><span class="glyphicon glyphicon-folder-open"></span> Proveedores</a></li>
							        @endif

							        @if( Auth::user()->hasPermissions(['items_consult']) )
							       	 	<li><a href="/insumos"><span class="glyphicon glyphicon-th"></span> Insumos</a></li>
							        @endif
		                </ul>
                	</li>
	            @endif


							{{-- Menu de inventario --}}
	            @if( Auth::user()->hasPermissions(['inventory_stock', 'inventory_movements', 'inventory_alerts']) )
								<li class="dropdown dropInv inve">
		                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-th-list"></span> Inventario <span class="caret"></span></a>
		                <ul class="dropdown-menu  dropdown-panel" role="menu">

											@if( Auth::user()->hasPermissions(['inventory_stock']) )
				            		<li><a href="{{route('invenInicio')}}"><span class="glyphicon glyphicon-equalizer"></span> Existencia</a></li>
											@endif

					            @if( Auth::user()->hasPermissions(['inventory_movements']) )
							  		 		<li><a href="{{route('entrPanel')}}"><span class="glyphicon glyphicon-circle-arrow-down"></span> Entradas</a></li>
							        @endif

							        @if( Auth::user()->hasPermissions(['inventory_movements']) )
							       	 	<li><a href="/salidas"><span class="glyphicon glyphicon-circle-arrow-up"></span> Salidas</a></li>
							        @endif

					            @if( Auth::user()->hasPermissions(['inventory_alerts']) )
					            	<li><a href="{{route('invenAlertsInicio')}}"><span class="glyphicon glyphicon-bell"></span> Alarmas</a></li>
				              @endif

					            @if( Auth::user()->hasPermissions(['inventory_alerts']) )
					            	<li><a href="{{route('invenModifInicio')}}"><span class="glyphicon glyphicon-edit"></span> Modificaciones</a></li>
				              @endif
			              </ul>
               	</li>
							 @endif


							@if( false )
								<li class="esta">
			          	<a href="/estadisticas"><span class="glyphicon glyphicon-tasks"></span> Estadisticas</a>
			          </li>
							@endif

							{{-- Menu de transferencias --}}
							@if( Auth::user()->hasPermissions(['movements_register_entry', 'movements_register_egress']) )

								<li class="dropdown dropTrn trn">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-transfer"></span> Tranferencias <span class="caret"></span></a>
                  <ul class="dropdown-menu  dropdown-panel" role="menu">
			              @if( Auth::user()->hasPermissions(['movements_register_entry']) )
					       			<li><a href="{{route('entrRegistrar')}}"><span class="glyphicon glyphicon-circle-arrow-down"></span> Registro de Entrada</a></li>
					       		@endif

					       		@if( Auth::user()->hasPermissions(['movements_register_egress']) )
					       			<li><a href="/registrarSalida"><span class="glyphicon glyphicon-circle-arrow-up"></span> Registro de Salida</a></li>
					       		@endif
		              </ul>
		            </li>
							@endif

            </ul>
        </nav>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                <span class="hamb-top"></span>
    			<span class="hamb-middle"></span>
				<span class="hamb-bottom"></span>
            </button>
            <div class="col-md-10 col-md-offset-1 front-page">
                <div class="row">
					@yield('front-page')
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
@endsection
