<form action="register" method="POST">
	
	{!! csrf_field() !!}

	@if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
	
	Nonbre:<input type="text" name="nombre">
	Apellido:<input type="text" name="apellido">
	Cedula:<input type="text" name="cedula">
	Email:<input type="text" name="email">
	Contraseña:<input type="password" name="password">
	Repetir Contraseña:<input type="password" name="password_confirmation"> 
	Departamento:
	<select name="rol">
		<option value="">Departamento</option>
		<option value="farmacia">Farmacia</option>
		<option value="alimentacion">Alimentacion</option>
	</select>
	Rango:
	<select name="rango">
		<option value="">Rango</option>
		<option value="director">Director</option>
		<option value="jefe">Jefe</option>
		<option value="empleado">Empleado</option>
	</select>
	<input type="submit" value="Registrar"/>
	
</form>