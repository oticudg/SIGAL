<?php

namespace App\Repositories;
use App\Lote;
use Carbon\Carbon;

class LotesRepository
{
	/**
	 * Registra un lote asociandolo con un insumo
	 *
	 * @param array $insumo
	 * @param int $deposito 
	 */
	public function registrar($insumo, $deposito){

		$vencimiento = Lote::where('insumo', $insumo['id'])
						   ->where('codigo', $insumo['lote'])
						   ->orderBy('id', 'desc')
						   ->value('vencimiento');

		$lote = new Lote();

		$lote->codigo = $insumo['lote'];
		$lote->insumo = $insumo['id'];
		$lote->cantidad = $insumo['cantidad'];
		$lote->deposito = $deposito;

		if( !$vencimiento and $insumo['fecha']){
			$vencimiento = $insumo['fecha'];
		}

		if($vencimiento)
			$lote->vencimiento = new Carbon($vencimiento); 

		$lote->save();	
	}

}