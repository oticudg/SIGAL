<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Kardex</title>
	<link rel="stylesheet" type="text/css" href="{{public_path().'/css/pdf.css'}}">
</head>
<body>

	<img class="cintillo" src="{{public_path().'/imagen/cintillo.jpg'}}">

  <h2 class="title" style="color:gray">KARDEX</h2>

  <table class="custon-table-bottom-off table-side">
		<tbody>
			<tr>
				<td class="green-td">CÓDIGO</td>
				<td>{{$insumoData->codigo}}</td>
				<td>DESCRIPCIÓN</td>
				<td>{{$insumoData->descripcion}}</td>
			</tr>
			<tr>
				<td>DEPÓSITO</td>
				<td>{{ strtoupper($deposito)}}</td>
				<td>USUARIO</td>
				<td>{{ strtoupper($usuario) }}</td>
			</tr>
			<tr>
					<td>DESDE</td>
					<td>{{$dateI}}</td>
					<td>HASTA</td>
					<td>{{$dateF}}</td>
			</tr>
		</tbody>
	</table>
	<br>

	<table class="custon-table-top-off">
		<thead>
			<tr>
				<th>FECHA</th>
				<th>PROCEDENCIA O DESTINO</th>
				<th>CONCEPTO</th>
				<th>TIPO</th>
				<th>MOV.</th>
				<th>EXIST.</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($movimientos as $movimiento)
				<tr>
					<td>{{$movimiento->fecha}}</td>
					<td class="decp">{{strtoupper($movimiento->pod)}}</td>
					<td class="decp">{{strtoupper($movimiento->concepto)}}</td>
					<td>{{strtoupper($movimiento->type)}}</td>
					<td>{{$movimiento->movido}}</td>
					<td>{{$movimiento->existencia}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>

</body>
</html>
