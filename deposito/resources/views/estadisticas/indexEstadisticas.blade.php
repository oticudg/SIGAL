@extends('panel')
@section('bodytag', 'ng-controller="estadisticasController"')
@section('addscript')
<script src="{{asset('js/estadisticasController.js')}}"></script>
@endsection

@section('front-page')
	
	<h5 class="text-muted">
		<span class="glyphicon glyphicon-cog"></span> Administración > 
		<span class="glyphicon glyphicon-tasks"></span> Estadísticas
	</h5>
	<br>
		
@endsection
