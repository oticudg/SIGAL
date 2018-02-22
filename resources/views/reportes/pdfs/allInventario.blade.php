<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>{{$title}}</title>
	<link rel="stylesheet" type="text/css" href="{{public_path().'/css/pdf.css'}}">
</head>
<body>

	<img class="cintillo" src="{{public_path().'/imagen/cintillo.jpg'}}">

	<h2 class="title" style="color:black">{{$title}}</h2>

	<table class="custon-table-bottom-off">
		<tbody>
			<tr>
				<th width="100">FECHA DEL INVENTARIO</th>
				<td>{{$date}}</td>
				<th>INSUMOS</th>
				<td>{{count($insumos)}}</td>
				<th>DEPÓSITO</th>
				<td>{{strtoupper($depositoN)}}</td>
			</tr>
			<tr>
				<th>FECHA DE IMPRESIÓN</th>
				<td>{{$fecha}}</td>
				<th>HORA</th>
				<td>{{$hora}}</td>
				<th>USUARIO</th>
				<td>{{ strtoupper($usuario) }}</td>
			</tr>
		</tbody>
	</table>

	<br>
	@foreach ($insumos as $insumo)
        <!--<table class="table-title custon-table-top-off custon-table-bottom-off">
            <tbody>
                <tr>
                    <td>Insumo</td>
                </tr>
            </tbody>
        </table>-->
        <table class="custon-table-top-off @if(count($insumo->lotes))custon-table-bottom-off @endif res">
            <!-- <thead>
                <tr>
                    <th>CANT. LOTES</th>
                    <th>CÓDIGO</th>
                    <th>DESCRIPCIÓN</th> 
                </tr>
            </thead> -->
            <tbody>
                    <tr>
                        <!--<th>CANT. LOTES</th>
                        <td class="decp">{{count($insumo->lotes)}}</td>-->
                        <th>CÓDIGO</th>
                        <td>{{$insumo->codigo}}</td>
                        <th>DESCRIPCIÓN</th> 
                        <td class="decp">{{$insumo->descripcion}}</td>
                    </tr>
            </tbody>
        </table>

        @if(count($insumo->lotes) > 0)
           <!-- <table class="table-title custon-table-top-off custon-table-bottom-off">
                <tbody>
                    <tr>
                        <td class="decp">Lotes: {{count($insumo->lotes)}}</td>
                    </tr>
                </tbody>
            </table>-->
            <table>
                <thead>
                <tr>
                    <th># LOTE</th>
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
                            <td>No se ha registrado la fecha de vencimiento</td>
                        @endif
                        <td class="cant">{{$lote->cantidad}}</td>
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="2"></td> 
                        <td class="cant">{{$insumo->existencia}}</td>
                    </tr>
                </tbody>
            </table>
        @endif
	@endforeach
</body>
</html>
