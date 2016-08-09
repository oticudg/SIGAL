<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Carga de Inventario</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
</head>
<body>

	<img class="cintillo" src="{{asset('imagen/cintillo.jpg')}}">

	<h2 class="title" style="color:gray">INVENTARIO INICIAL</h2>
{{--
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
--}}
	<table class="custon-table-bottom-off table-side">
		<tbody>
			<tr>
				<td class="green-td" width="100">FECHA DEL REGISTRO</td>
				<td>{{$carga->fecha}}</td>
				<td>CÓDIGO</td>
				<td>{{substr($carga->codigo,11)}}</td>
				<td>DEPÓSITO</td>
				<td>{{strtoupper($carga->deposito)}}</td>
			</tr>
			<tr>
				<td>HORA</td>
				<td>{{$carga->hora}}</td>
				<td>INSUMOS</td>
				<td>{{count($insumos)}}</td>
				<td>USUARIO</td>
				<td>{{ strtoupper($carga->usuario) }}</td>
			</tr>
		</tbody>
	</table>
	<br>
	<table class="custon-table-top-off">
		<thead>
			<tr>
				<th>CÓDIGO</th>
				<th>DESCRIPCIÓN</th>
				<th>CANTIDAD</th>
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
