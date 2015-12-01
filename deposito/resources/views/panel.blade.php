@extends('base')
@section('conten')

	<nav id="side-menu" class="col-md-2 navbar navbar-inverse custon-bar">
		<accordion class="accordion-body">
		    <accordion-group is-disabled="true">
		    	<accordion-heading>
            		<a href="/inicio" id="onlyLink"><span class="glyphicon glyphicon-home"></span> Inicio</a>
        		</accordion-heading>
		    </accordion-group>
			
			@if( Auth::user()->haspermission('usuarios') || Auth::user()->haspermission('departamentos') 
				|| Auth::user()->haspermission('provedores') || Auth::user()->haspermission('insumos') ||
				Auth::user()->haspermission('inventarios') || Auth::user()->haspermission('entradas') ||
				Auth::user()->haspermission('salidas') || Auth::user()->haspermission('modificaciones'))
			

			    <accordion-group>
			    	<accordion-heading>
	            		<span class="glyphicon glyphicon-cog"></span> Administración
	        		</accordion-heading>
	        	   
	        	   @if( Auth::user()->haspermission('usuarios') )	
			      	 	<div class="enlace"><a href="/usuarios"><span class="glyphicon glyphicon-user"></span> Usuario</a></div>
			       @endif
			       
			       @if( Auth::user()->haspermission('departamentos') )
				   	 	<div class="enlace"><a href="/departamentos"><span class="glyphicon glyphicon-briefcase"></span> Departamentos</a></div>
			       @endif
			       
			       @if( Auth::user()->haspermission('provedores') )
			       	 	<div class="enlace"><a href="/proveedores"><span class="glyphicon glyphicon-folder-open"></span> Proveedores</a></div>
			       @endif
			       
			       @if( Auth::user()->haspermission('insumos') )
			       	 	<div class="enlace"><a href="/insumos"><span class="glyphicon glyphicon-th"></span> Insumos</a></div>
			       @endif
			       
			       @if( Auth::user()->haspermission('inventarios') )
			       	 	<div class="enlace"><a href="/inventario"><span class="glyphicon glyphicon-th-list"></span> Inventario</a></div>
			       @endif
			       
			       @if( Auth::user()->haspermission('entradas') )
			  		 	<div class="enlace"><a href="/entradas"><span class="glyphicon glyphicon-circle-arrow-down"></span> Entradas</a></div>
			       @endif
			       
			       @if( Auth::user()->haspermission('salidas') )
			       	 	<div class="enlace"><a href="/salidas"><span class="glyphicon glyphicon-circle-arrow-up"></span> Salidas</a></div>
			       @endif
			       
			       @if( Auth::user()->haspermission('modificaciones') )
			       	 	<div class="enlace"><a href="/modificaciones"><span class="glyphicon glyphicon glyphicon-edit"></span> Modificaciones</a></div>
			       @endif
			    </accordion-group>
			@endif
			
		    @if( Auth::user()->haspermission('estadisticas') )
				<accordion-group is-disabled="true">
			    	<accordion-heading>
	            		<a href="/estadisticas" id="onlyLink"><span class="glyphicon glyphicon-tasks"></span> Estadísticas</a>
	        		</accordion-heading>
			    </accordion-group>
			@endif
			
			@if( Auth::user()->haspermission('entradaR') || Auth::user()->haspermission('salidaR') )
				<accordion-group>
			    	<accordion-heading>
	            		<span class="glyphicon glyphicon-transfer"></span> Tranferencias
	        		</accordion-heading>
	        	    @if( Auth::user()->haspermission('entradaR') )
			       		<div class="enlace"><a href="/registrarEntrada"><span class="glyphicon glyphicon-circle-arrow-down"></span> Registro de Entrada</a></div>
			       	@endif
			       	@if( Auth::user()->haspermission('salidaR') )
			       		<div class="enlace"><a href="/registrarSalida"><span class="glyphicon glyphicon-circle-arrow-up"></span> Registro de Salida</a></div>
			       	@endif
			    </accordion-group>
			@endif
		</accordion>
	</nav>

	<div id="front-page" class="col-md-10 col-md-offset-2">
		@yield('front-page')
	</div>

@endsection