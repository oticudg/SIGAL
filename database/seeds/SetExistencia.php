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

  				$existencia = 0;

  				foreach ($movimientos as $movimiento){

  					if( $movimiento->type == 'salida'){
  						$existencia -= $movimiento->movido;

  						DB::table('insumos_salidas')->where('id', $movimiento->id)->update([
  							'existencia' => $existencia
  						]);

  						continue;
  					}

  					if( $movimiento->type == 'cinventario')
  						$existencia = $movimiento->movido;
  					else
  						$existencia += $movimiento->movido;

  					DB::table('insumos_entradas')->where('id', $movimiento->id)->update([
  						'existencia' => $existencia
  					]);
  				}

  			}
  		}
    }
}
