@extends('departamentos.indexDepartamentos')

@section('front-page')
<div>
	<h3 style="color:#54AF54;"><span class="glyphicon glyphicon-plus"></span> Nuevo Departamento</h3>
	<hr>

	<form method="POST" action="/registrarDepartamento" enctype="multipart/form-data">
  		
  		{!! csrf_field() !!}

  		@if($errors->has())
  			<div class="alert alert-danger">
  				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  				{{$errors->first()}}
  			</div>
  		@endif

  		@if( isset($success) )
  			<div class="alert alert-success">
  				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  				{{$success}}
  			</div>
  		@endif

		<div class="row">
			<div class="col-md-4 col-md-offset-2">
				<input class="form-control" type="text" placeholder="Nombre" name="nombre" value="{{old('nombre')}}">
			</div>

			<div class="col-md-4">
				<input class="form-control" type="text" placeholder="División" name="division" value="{{old('division')}}">
			</div>
		</div>
		
		<br><br>

		<div class="row">
			<center>
				<div class="col-md-4 col-md-offset-2">
					<label>Sello</label>
					<input ngf-select ng-model="sello" type="file" name="sello"/>
				</div>

				<div class="col-md-4">
					<label>Firma</label>
					<input ngf-select ng-model="firma" type="file" name="firma">
				</div>
			</center>
		</div>
		
		<br><br>
		
		<div class="row">
			<div class="col-md-4 col-md-offset-2">
				<img ng-show="sello[0] != null" ngf-src="sello[0]" class="img-thumbnail img-lg">
			</div>

			<div class="col-md-4">
				<img ng-show="firma[0] != null" ngf-src="firma[0]" class="img-thumbnail img-lg">
			</div>
		</div>

		<hr>
		<center>
			@if(!isset($success))
				<button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok-sign"></span> Registrar</button>
			@endif
		</center>
	</form>
	
	<center>
		@if(isset($success))
			<a href="/departamentos" class="btn btn-success" style="color:white"><span class="glyphicon glyphicon-ok-sign"></span> Ok</a>
		@endif
	</center>
	

</div>
@endsection	
