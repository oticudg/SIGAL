<?php

namespace App\Repositories;
use App\Lote;
use Carbon\Carbon;
use Auth;

class LotesRepository
{
	/**
	 * Registra un lote asociandolo con un insumo
	 *
	 * @param array $insumo
	 * @param int $deposito 
	 */
	public function registrar($insumo){

		$vencimiento = Lote::where('insumo', $insumo['id'])
						   ->where('codigo', $insumo['lote'])
						   ->where('deposito', Auth::user()->deposito)
						   ->orderBy('id', 'desc')
						   ->value('vencimiento');

		$lote = new Lote();

		$lote->codigo = $insumo['lote'];
		$lote->insumo = $insumo['id'];
		$lote->cantidad = $insumo['cantidad'];
		$lote->deposito = Auth::user()->deposito;

		if( !$vencimiento and (isset($insumo['fecha']) and !empty($insumo['fecha']))){
			$vencimiento = $insumo['fecha'];
		}

		if($vencimiento)
			$lote->vencimiento = new Carbon($vencimiento); 

		$lote->save();	
	}

	/**
	 * Devuelve insumos asociado con un lote que tengan una fecha de  
	 * vencimiento diferente a la previamente almacenada.
	 * 
	 * @param array $insumos
	 * @param array $errores 
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
	 * @param array $errores 
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

}