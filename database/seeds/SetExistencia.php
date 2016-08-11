<?php

use Illuminate\Database\Seeder;

class SetExistencia extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $depositos = DB::table('depositos')->lists('id');

  		foreach ($depositos as $deposito) {

  			$insumos = DB::table('insumos_entradas')->where('deposito', $deposito)->distinct('insumo')->lists('insumo');

  			foreach ($insumos as $insumo){

  				$entradas = DB::table('insumos_entradas')->where('insumo',$insumo)
  										->where('insumos_entradas.deposito', $deposito)
  										->join('entradas', 'insumos_entradas.entrada' , '=', 'entradas.id')
  										->select('cantidad as movido', 'entradas.type', 'insumos_entradas.created_at', 'insumos_entradas.id');

  				$salidas = DB::table('insumos_salidas')->where('insumo',$insumo)
  										->where('insumos_salidas.deposito', $deposito)
  										->join('salidas', 'insumos_salidas.salida' , '=', 'salidas.id')
  										->select('despachado as movido', DB::raw('("salida") as type'), 'insumos_salidas.created_at', 'insumos_salidas.id');


  			  $movimientos =  $salidas->unionAll($entradas)
  										 ->orderBy('created_at','asc')
  										 ->get();

  				$init_year_search = date('Y-01-01 00:00:00',strtotime('2016-01-01'));

  				foreach ($movimientos as $movimiento){

            //Obtiene la fecha de la ultima carga de inventario realizada en el primer año del rango de fecha a consultar
            $last_cinve = DB::table('entradas')
                          ->where('deposito', $deposito)
                          ->where('type','cinventario')
                          ->whereBetween('created_at', [$init_year_search, $movimiento->created_at])
                          ->orderBy('id', 'desc')
                          ->value('created_at');
            /**
             *Si se ha encontrado una carga de inventario en el primer año del rango de fecha a consultar,
             *construye consulta que obtienen todas las entradas y salidas desde la fecha de dicha carga de
             *inventario hasta la fecha del primer movimiento encontrado, de lo contrario construye consulta
             *que obtiene todas las entradas y salidas desde el primer año del rango de fecha a consultar
             *hasta la fecha del primer movimiento encontrado.
             */
            if(!empty($last_cinve) ){
              $queryE = DB::table('insumos_entradas')->where('insumo', $insumo)
                     ->where('deposito', $deposito)
                     ->whereBetween('created_at',[$last_cinve, $movimiento->created_at]);
              $queryS = DB::table('insumos_salidas')->where('insumo', $insumo)
                     ->where('deposito', $deposito)
                     ->whereBetween('created_at',[$last_cinve, $movimiento->created_at]);
            }

            /**
             *Realiza la consulta que Obtiene la cantidad de salidas y entradas
             *de los movimientos de la consultas que se almacenan en $queryE, $queryS.
             */
            $entradaM = $queryE->sum('cantidad');
            $salidaM  = $queryS->sum('despachado');
            $existencia = $entradaM - $salidaM;

  					if( $movimiento->type == 'salida'){

  						DB::table('insumos_salidas')->where('id', $movimiento->id)->update([
  							'existencia' => round($existencia, 2)
  						]);

  						continue;
  					}

  					DB::table('insumos_entradas')->where('id', $movimiento->id)->update([
  						'existencia' => round($existencia, 2)
  					]);
  				}
  			}
  		}
    }
}
