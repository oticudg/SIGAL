<?php

namespace App\Repositories;
use App\Lote;
use Carbon\Carbon;
use Auth;
use DB;

class LotesRepository
{
	/**
	 * Registra un lote asociandolo con un insumo o agrega cantidad
	 * a un lote existente.
	 *
	 * @param array $insumo
	 */
	public function registrar($insumo){

		$loteRegister = Lote::where('insumo', $insumo['id'])
						   ->where('codigo', $insumo['lote'])
						   ->where('deposito', Auth::user()->deposito)
						   ->orderBy('id', 'desc')
						   ->first();

		if($loteRegister){
			$loteRegister->cantidad = $loteRegister->cantidad + $insumo['cantidad'];

			if(!$loteRegister->vencimiento and (isset($insumo['fecha']) and !empty($insumo['fecha']))){
				$loteRegister->vencimiento = new Carbon($insumo['fecha']);
			}				

			$loteRegister->save();
		}
		else{

			$lote = new Lote();

			$lote->codigo = $insumo['lote'];
			$lote->insumo = $insumo['id'];
			$lote->cantidad = $insumo['cantidad'];
			$lote->deposito = Auth::user()->deposito;

			if( isset($insumo['fecha']) and !empty($insumo['fecha']) ){
				$lote->vencimiento = new Carbon($insumo['fecha']);
			}	

			$lote->save();	
		}
	}

	/**
	 * reduce la cantidad de un lote asociando con un insumo 
	 *
	 * @param array $insumo
	 */
	public function reducir($insumo){
		
		$insumoRegister =  Lote::where('insumo', $insumo['id'])
						   ->where('codigo', $insumo['lote'])
						   ->where('deposito', Auth::user()->deposito)
						   ->orderBy('id', 'desc')
						   ->first();			

		$insumoRegister->cantidad = $insumoRegister->cantidad - $insumo['despachado'];

		$insumoRegister->save();
	}

	/**
	 * Devuelve insumos asociado con un lote que tengan una fecha de  
	 * vencimiento diferente a la previamente almacenada.
	 * 
	 * @param array $insumos
	 * @return array $errores 
	 */
	public function nequal_vencimiento($insumos){

		$errores = [];

		foreach($insumos as $insumo){

            if(isset($insumo['fecha']) and !empty($insumo['fecha'])){

                $vencimiento = Lote::where('insumo', $insumo['id'])
                            ->where('codigo', $insumo['lote'])
                            ->where('deposito', Auth::user()->deposito)
                            ->orderBy('id', 'desc')
                            ->value('vencimiento');

                if($vencimiento and $vencimiento->ne(new Carbon($insumo['fecha']))){
                	array_push($errores, $insumo['id']);
                }
            }
	    }

	    return $errores;
	}

	/**
	 * Devuelve insumos cuyo lotes esten duplicados en el 
	 * arreglo que se pase. 
	 * 
	 * @param array $insumos
	 * @return array $errores 
	 */
	public function equal_insumos_lotes($insumos){

		$errores = [];

	 	foreach ($insumos as $key => $insumo){
                
            foreach( $insumos as $key_validate => $insumo_validate){

                if($key == $key_validate)
                    continue;

                if($insumo_validate['id'] == $insumo['id']){
                    if($insumo_validate['lote'] == $insumo['lote'])
                    	array_push($errores, $insumo['id']);
                }
            }               
        }


	    return $errores;
	}

	/**
	 * Devuelve insumos cuyos lotes no existan en el 
	 * arreglo que se pase. 
	 * 
	 * @param array $insumos
	 * @return array $errores 
	 */
	public function loteExist($insumos){

		$errores = [];

	 	foreach ($insumos as $key => $insumo){

	 		$loteRegister = Lote::where('insumo', $insumo['id'])
						   	->where('codigo', $insumo['lote'])
						   	->where('deposito', Auth::user()->deposito)
						   	->orderBy('id', 'desc')
						   	->first(); 

            if(!$loteRegister){
                array_push($errores, ['insumo' => $insumo['id'], 'lote' => $insumo['lote']]);
            }
        }

	    return $errores;
	}

	/**
	 * Devuelve insumos cuyos lotes no tengan la cantidad
	 * especificada en el arreglo que se pase. 
	 * 
	 * @param array $insumos
	 * @return array $errores 
	 */
	public function saldoExist($insumos){

		$errores = [];

	 	foreach ($insumos as $key => $insumo){

	 		$loteRegister = Lote::where('insumo', $insumo['id'])
						   	->where('codigo', $insumo['lote'])
						   	->where('deposito', Auth::user()->deposito)
						   	->orderBy('id', 'desc')
						   	->first(); 

            if( ($loteRegister->cantidad - $insumo['despachado']) < 0){
                array_push($errores, ['insumo' => $insumo['id'], 'lote' => $insumo['lote']]);
            }
        }

	    return $errores;
	}

	/**
	 * Devuelve todos los lotes de un insumo que se pase 
	 * 
	 * @param int $insumo
	 * @return Illuminate\Database\Eloquent\Collection $lotes
	 */
	public function lotes($insumo){

		$lotes =  Lote::where('insumo', $insumo)
					  ->where('cantidad', '>', 0)	
					  ->where('deposito', Auth::user()->deposito)
					  ->select('codigo','cantidad', DB::raw('DATE_FORMAT(vencimiento, "%d/%m/%Y") as fecha'))
					  ->get();

		return $lotes;
	}
}