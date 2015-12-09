@extends('panel')



@section('front-page')

	<div id="bienvenida">
		<div>
			<h1 class="text-title">Bienvenido</h1>
			<hr>
			<h3>La oficina de tecnologías de información y comunicación OTIC le da la bienvenida al sistema de gestion de deposito del Hospital Universitario de Maracaibo.</h3>		
		</div>
	</div>
	
	<div id="contenido">
		
		<h1 class="text-title">Gestiona Inteligentemente</h1>

		<div id="contenedor-imagen">
			<img id="imagen" src="{{asset('imagen/sistema.png')}}" class="img-rounded custon-imagen">
		</div>

		<div class="row">
			<div class="col-md-4 col-md-offset-2">
				<h3 class="text-title">Gestiona el inventario de una forma mas rápida</h3>
				<p class="text-muted">Gestiona todos los insumos en el inventario de una forma muy rápida. Consulta, agrega, elimina y rastrea la ubicación de cada producto.</p>
			</div>

			<div class="col-md-4">
				<h3 class="text-title">Recibe notificaciones del estado de los productos</h3>
				<p class="text-muted">Recibe notificaciones del estado de todos los productos que se encuentran en el inventario. Los que están próximos a expirar, los que se encuentra  en niveles bajos y mas.</p>
			</div>

		</div>
	</div>
@endsection