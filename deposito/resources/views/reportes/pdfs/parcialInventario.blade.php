<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Inventario Parcial</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
</head>
<body>
	
	<img class="cintillo" src="{{asset('imagen/cintillo.jpg')}}">

	<h1 class="title">Inventario Parcial</h1>	

	<table class="custon-table-bottom-off">
		<thead>
			<tr>
				<th>Fecha de Reporte</th>
				<th>Hora</th>
				<th>Usuario</th>
				<th>Depósito</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{$fecha}}</td>
				<td>{{$hora}}</td>
				<td>{{$usuario}}</td>
				<td>{{$depositoN}}</td>
			</tr>
		</tbody>
	</table>
	
	<table class="custon-table-top-off">
		<thead>
			<tr>
				<th>Código</th>
				<th>Descripción</th>
				<th>Existencía</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($insumos as $insumo)
				<tr>
					<td>{{$insumo->codigo}}</td>
					<td class="decp">{{$insumo->descripcion}}</td>
					<td>{{$insumo->existencia}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>