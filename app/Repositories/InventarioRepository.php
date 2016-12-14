<?php

namespace App\Repositories;

use App\Inventario;

class InventarioRepository
{	
	/**
	 * Devuelve todos los insumos cuyos valores de despacho  
	 * sean mayores que la existencia en el inventario. 
	 *
	 * @param array $insumos
	 * @param int $deposito
	 * @return array $invalidos
	 */
    public function validaExistencia($insumos, $deposito){

		$invalidos = [];

		$insumos = $this->agruparInsumos($insumos);

		foreach ($insumos as $insumo){

		    $inventario = Inventario::where('insumo' , $insumo['id'])
		                  ->where('deposito', $deposito)
		                  ->first();

		    $existencia = Inventario::where('insumo' , $insumo['id'])
		                  ->where('deposito', $deposito)
		                  ->value('existencia');

		    if( !$inventario || $existencia < $insumo['despachado'])
		        array_push($invalidos, $insumo['id']);
		}

		return $invalidos;
	}


	/**
	 * Agrupa todos los insumos duplicados.   
	 *
	 * @param array $insumos
	 * @return array $groups
	 */
	public function agruparInsumos($insumos){

		$groups = [];	

		foreach ($insumos as $index => $insumo){

			if( array_search($insumo['id'], array_column($groups, 'id')) !== false )
				continue;

			$cantidad = $insumo['despachado'];

			foreach ($insumos as $key => $value) {
				
				if($key == $index)
					continue;

				if($insumo['id'] == $value['id'])
					$cantidad += $value['despachado'];
			}

			array_push($groups, ['id' => $insumo['id'], 'despachado' => $cantidad]);
		}

		return $groups;
	}	
}