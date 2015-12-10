@extends('base')
@section('conten')

	<div id='loader' class="div_loader">
		<div id="img_loader" class="img_loader">
			<img src="{{asset('imagen/loader.gif')}}" alt="">
			<p> Cargando ...</p>
		</div>
	</div>
	
	<div id="wrapper">
        <div class="overlay"></div>
    
        <!-- Sidebar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                       Memu
                    </a>
                </li>
                
                <li>
                    <a href="/inicio">Inicio</a>
                </li>

                @if( Auth::user()->haspermission('usuarios') || Auth::user()->haspermission('departamentos') 
				|| Auth::user()->haspermission('provedores') || Auth::user()->haspermission('insumos') ||
				Auth::user()->haspermission('inventarios') || Auth::user()->haspermission('entradas') ||
				Auth::user()->haspermission('salidas') || Auth::user()->haspermission('modificaciones'))
	                <li class="dropdown droAdmin">
		                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Administración <span class="caret"></span></a>
		                <ul class="dropdown-menu  dropdown-panel" role="menu">
			                @if( Auth::user()->haspermission('usuarios') )	
		                       <li><a href="/usuarios"><span class="glyphicon glyphicon-user"></span> Usuario</a></li>
		                    @endif
		                    @if( Auth::user()->haspermission('departamentos') )
					   	 	   <li><a href="/departamentos"><span class="glyphicon glyphicon-briefcase"></span> Departamentos</a></li>
				       		@endif
		                    
					        @if( Auth::user()->haspermission('provedores') )
					       	 	<li><a href="/proveedores"><span class="glyphicon glyphicon-folder-open"></span> Proveedores</a></li>
					        @endif
					       
					        @if( Auth::user()->haspermission('insumos') )
					       	 	<li><a href="/insumos"><span class="glyphicon glyphicon-th"></span> Insumos</a></li>
					        @endif
					       
					        @if( Auth::user()->haspermission('inventarios') )
					       	 	<li><a href="/inventario"><span class="glyphicon glyphicon-th-list"></span> Inventario</a></li>
					        @endif
					       
					        @if( Auth::user()->haspermission('entradas') )
					  		 	<li><a href="/entradas"><span class="glyphicon glyphicon-circle-arrow-down"></span> Entradas</a></li>
					        @endif
					       
					        @if( Auth::user()->haspermission('salidas') )
					       	 	<li><a href="/salidas"><span class="glyphicon glyphicon-circle-arrow-up"></span> Salidas</a></li>
					        @endif
		                </ul>
                	</li>
	            @endif

	            @if( Auth::user()->haspermission('modificaciones') )
					<li class="dropdown dropMod">
		                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon glyphicon-edit"></span> Modificaciones <span class="caret"></span></a>
		                <ul class="dropdown-menu  dropdown-panel" role="menu">
			             	<li><a href="{{route('modifiEntrada')}}"><span class="glyphicon glyphicon-circle-arrow-down"></span> Entradas</a></li>
			       			<li><a href="{{route('modifiSalida')}}"><span class="glyphicon glyphicon-circle-arrow-up"></span> Salidas</a></li>
		                </ul>
                	</li>
				@endif
				
				@if( Auth::user()->haspermission('estadisticas') )
					<li>
                    	<a href="/estadisticas"><span class="glyphicon glyphicon-tasks"></span> Estadisticas</a>
                	</li>					
				@endif
				
				@if( Auth::user()->haspermission('entradaR') || Auth::user()->haspermission('salidaR') )

					<li class="dropdown dropTrn">
	                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-transfer"></span> Tranferencias <span class="caret"></span></a>
	                  <ul class="dropdown-menu  dropdown-panel" role="menu">
	                    @if( Auth::user()->haspermission('entradaR') )
			       			<li><a href="/registrarEntrada"><span class="glyphicon glyphicon-circle-arrow-down"></span> Registro de Entrada</a></li>
			       		@endif

			       		@if( Auth::user()->haspermission('salidaR') )
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