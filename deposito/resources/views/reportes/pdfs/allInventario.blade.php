<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Inventario Total</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
</head>
<body>

	<img class="cintillo" src="{{asset('imagen/cintillo.jpg')}}">

	<h1 class="title" style="color:gray">Inventario Total</h1>

	<table class="custon-table-bottom-off table-side">
		<tbody>
			<tr>
				<td class="green-td" width="100">FECHA DEL INVENTARIO</td>
				<td>{{$date}}</td>
				<td>INSUMOS</td>
				<td>{{count($insumos)}}</td>
				<td>DEPÓSITO</td>
				<td>{{strtoupper($depositoN)}}</td>
			</tr>
			<tr>
				<td>FECHA DE GENERACIÓN</td>
				<td>{{$fecha}}</td>
				<td>HORA</td>
				<td>{{$hora}}</td>
				<td>USUARIO</td>
				<td>{{ strtoupper($usuario) }}</td>
			</tr>
		</tbody>
	</table>

	<br>
	<table class="custon-table-top-off">
		<thead>
			<tr>
				<th>CÓDIGO</th>
				<th>DESCRIPCIÓN</th>
				<th>EXISTENCIA</th>
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
