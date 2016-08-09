<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Salida</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
</head>
<body>

	<img class="cintillo" src="{{asset('imagen/cintillo.jpg')}}">

  <h2 class="title" style="color:gray">PRO-FORMA DE PEDIDO</h2>

  <table class="custon-table-bottom-off table-side">
		<tbody>
			<tr>
				<td class="green-td">CÓDIGO</td>
				<td>{{substr($salida->codigo,11)}}</td>
        <td>DEPÓSITO</td>
				<td>{{ strtoupper($salida->deposito)}}</td>
			</tr>
			<tr>
          <td>FECHA DE REGISTRO</td>
          <td>{{$salida->fecha}}</td>
					<td>TERCERO</td>
					<td>{{ strtoupper($salida->tercero) }}</td>
			</tr>
			<tr>
				<td>HORA</td>
				<td>{{$salida->hora}}</td>
				<td>USUARIO</td>
				<td>{{ strtoupper($salida->usuario) }}</td>
			</tr>
			<tr>
				<td>INSUMOS</td>
				<td>{{count($insumos)}}</td>
				<td>CONCEPTO</td>
				<td>{{ strtoupper($salida->concepto)}}</td>
			</tr>
		</tbody>
	</table>
	<br>

	<table class="custon-table-top-off">
		<thead>
			<tr>
				<th>CÓDIGO</th>
				<th>DESCRIPCIÓN</th>
				<th>SOLICITADO</th>
				<th>DESPACHADO</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($insumos as $insumo)
				<tr>
					<td width="100">{{$insumo->codigo}}</td>
					<td width="275" class="decp">{{$insumo->descripcion}}</td>
					<td width="10">{{$insumo->solicitado}}</td>
					<td width="10">{{$insumo->despachado}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>
