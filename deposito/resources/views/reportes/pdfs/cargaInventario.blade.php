<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Carga de Inventario</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
</head>
<body>
	
	<img class="cintillo" src="{{asset('imagen/cintillo.jpg')}}">

	<h1 class="title">Inventario Inicial</h1>

	<h2 class="codigo">Código: {{ substr($carga->codigo,11)}}</h2>	

	<table class="custon-table-bottom-off">
		<thead>
			<tr>
				<th>Fecha de registro</th>
				<th>Hora</th>
				<th>Usuario</th>
				<th>Depósito</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{$carga->fecha}}</td>
				<td>{{$carga->hora}}</td>
				<td>{{$carga->usuario}}</td>
				<td>{{$carga->deposito}}</td>
			</tr>
		</tbody>
	</table>
	
	<table class="custon-table-top-off">
		<thead>
			<tr>
				<th>Código</th>
				<th>Descripción</th>
				<th>Cantidad</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($insumos as $insumo)
				<tr>
					<td>{{$insumo->codigo}}</td>
					<td class="decp">{{$insumo->descripcion}}</td>
					<td>{{$insumo->cantidad}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>