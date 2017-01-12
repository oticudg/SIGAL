<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" type="text/css" href="{{public_path().'/css/excel.css'}}">
	</head>
	<body>	

		<tr class="head-title">
			<td colspan="3">DATOS DEL REPORTE</td>
		</tr>
		<tr class="head">
			<td>Fecha de inventario</td>
			<td>Fecha de generacion</td>
			<td>Hora</td>
		</tr>

		<tr>
			<td>{{date("d/m/Y",strtotime($date))}}</td>
			<td>{{$fecha}}</td>
			<td>{{$hora}}</td>
		</tr>

		<tr class="head">
			<td>Cantidad de insumos</td>
			<td>Almacén</td>
			<td>Usuario</td>
		</tr>

		<tr>
			<td>{{count($insumos)}}</td>
			<td>{{strtoupper($depositoN)}}</td>
			<td>{{strtoupper($usuario)}}</td>
		</tr>

		<tr class="head-title">
			<td colspan="3">Insumos</td>
		</tr>

		<tr class="head">
			<td>CODIGO</td>
			<td width="60">DESCRIPCIÓN</td>
			<td>EXISTENCIA</td>
		</tr>

		@foreach($insumos as $insumo)
			<tr class="cell">
			
				<td>{{$insumo->codigo}}</td>
				<td>{{$insumo->descripcion}}</td>
				<td>{{$insumo->existencia}}</td>
			</tr>
		@endforeach
	</body>
</html>