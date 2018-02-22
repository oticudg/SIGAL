<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{{$title}}</title>
	<link rel="stylesheet" type="text/css" href="{{public_path().'/css/pdf.css'}}">
</head>
<body>

	<img class="cintillo" src="{{public_path().'/imagen/cintillo.jpg'}}">

	<h2 class="title" style="color:gray">{{$title}}</h2>

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
				<td>FECHA DE IMPRESIÓN</td>
				<td>{{$fecha}}</td>
				<td>HORA</td>
				<td>{{$hora}}</td>
				<td>USUARIO</td>
				<td>{{ strtoupper($usuario) }}</td>
			</tr>
		</tbody>
	</table>

	<br>
	@foreach ($insumos as $insumo)
        <table class="table-title custon-table-top-off custon-table-bottom-off">
            <tbody>
                <tr>
                    <td>Insumo</td>
                </tr>
            </tbody>
        </table>
        <table class="custon-table-top-off @if(count($insumo->lotes))custon-table-bottom-off @endif">
            <thead>
                <tr>
                    <th>CÓDIGO</th>
                    <th>DESCRIPCIÓN</th>
                    <th>EXISTENCIA</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td>{{$insumo->codigo}}</td>
                        <td class="decp">{{$insumo->descripcion}}</td>
                        <td>{{$insumo->existencia}}</td>
                    </tr>
            </tbody>
        </table>

        @if(count($insumo->lotes) > 0)
            <table class="table-title custon-table-top-off custon-table-bottom-off">
                <tbody>
                    <tr>
                        <td class="decp">Lotes: {{count($insumo->lotes)}}</tdi>
                    </tr>
                </tbody>
            </table>
            <table class="custon-table-top-off">
                <thead>
                <tr>
                    <th>CÓDIGO</th>
                    <th>FECHA DE VENCIMIENTO</th>
                    <th>CANTIDAD</th>
                </tr>
                </thead>
                <tbody>
                @foreach($insumo->lotes as $lote)
                    <tr>
                        <td>{{$lote->codigo}}</td>
                        @if($lote->vencimiento)
                            <td>{{$lote->vencimiento->format('d/m/Y')}}</td>
                        @else
                            <td></td>
                        @endif
                        <td>{{$lote->cantidad}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
	@endforeach
</body>
</html>
