<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Salida</title>
	<link rel="stylesheet" type="text/css" href="{{asset('css/pdf.css')}}">
</head>
<body>

	<img class="cintillo" src="{{asset('imagen/cintillo.jpg')}}">

  <h2 class="title" style="color:gray">Pro-Forma de pedido</h2>

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
					<td>HORA</td>
					<td>{{$salida->hora}}</td>
			</tr>
			<tr>
				<td>SERVICIO</td>
				<td>{{ strtoupper($salida->departamento) }}</td>
				<td>USUARIO</td>
				<td>{{ strtoupper($salida->usuario) }}</td>
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
