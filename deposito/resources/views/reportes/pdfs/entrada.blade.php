<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Entrada</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
</head>
<body>

	<img class="cintillo" src="{{asset('imagen/cintillo.jpg')}}">

  <h2 class="title" style="color:gray">Pro-Forma de entrada</h2>

  <table class="custon-table-bottom-off table-side">
		<tbody>
			<tr>
				<td class="green-td">CÓDIGO</td>
				<td>{{substr($entrada->codigo,11)}}</td>
				<td>FECHA DE REGISTRO</td>
				<td>{{$entrada->fecha}}</td>
			</tr>
			<tr>
				<td>TIPO</td>
				<td>{{ strtoupper($entrada->type) }}</td>
				<td>PROVEEDOR</td>
				<td>{{ strtoupper($entrada->provedor) }}</td>
			</tr>
			<tr>
				<td>DEPÓSITO</td>
				<td>{{ strtoupper($entrada->deposito)}}</td>
				<td>USUARIO</td>
				<td>{{ strtoupper($entrada->usuario) }}</td>
			</tr>
			<tr>
					<td>ORDEN</td>
					<td>{{$entrada->orden or 'N/A'}}</td>
					<td>Hora</td>
					<td>{{$entrada->hora}}</td>
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
