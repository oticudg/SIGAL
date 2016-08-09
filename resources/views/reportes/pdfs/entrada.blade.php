<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Entrada</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
</head>
<body>

	<img class="cintillo" src="{{asset('imagen/cintillo.jpg')}}">

  <h2 class="title" style="color:gray">PRO-FORMA DE ENTRADA</h2>

  <table class="custon-table-bottom-off table-side">
		<tbody>
			<tr>
				<td class="green-td">CÓDIGO</td>
				<td>{{substr($entrada->codigo,11)}}</td>
				<td>DEPÓSITO</td>
				<td>{{ strtoupper($entrada->deposito)}}</td>
			</tr>
			<tr>
				<td>FECHA DE REGISTRO</td>
				<td>{{$entrada->fecha}}</td>
				<td>TERCERO</td>
				<td>{{ strtoupper($entrada->tercero) }}</td>
			</tr>
			<tr>
				<td>HORA</td>
				<td>{{$entrada->hora}}</td>
				<td>USUARIO</td>
				<td>{{ strtoupper($entrada->usuario) }}</td>
			</tr>
			<tr>
					<td>INSUMOS</td>
					<td>{{count($insumos)}}</td>
					<td>CONCEPTO</td>
					<td>{{ strtoupper($entrada->concepto) }}</td>
			</tr>
		</tbody>
	</table>
	<br>

	<table class="custon-table-top-off">
		<thead>
			<tr>
				<th>CÓDIGO</th>
				<th>DESCRIPCIÓN</th>
				<th>LOTE</th>
				<th>FECHA VTO</th>
				<th>CANTIDAD</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($insumos as $insumo)
				<tr>
					<td>{{$insumo->codigo}}</td>
					<td class="decp">{{$insumo->descripcion}}</td>
					<td>{{$insumo->lote}}</td>
					<td>{{$insumo->fecha}}</td>
					<td>{{$insumo->cantidad}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>
