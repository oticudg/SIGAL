<?php

namespace App\Repositories;
use App\Lote;
use Carbon\Carbon;
use Auth;
use DB;
use App\Repositories\InventarioRepository;

class LotesRepository
{
	/**
	 * Registra un lote asociandolo con un insumo o agrega cantidad
	 * a un lote existente.
	 *
	 * @param array $insumo
	 */
	public function registrar($insumo){

		if( !($this->hasLote($insumo)) ){

			$inventario = new InventarioRepository();
			$existencia = $inventario->balance(
				$insumo['id'],
				Auth::user()->deposito
			);

			$lote = new Lote();
			$lote->codigo = 'S/L';
			$lote->insumo = $insumo['id'];
			$lote->cantidad = $existencia;
			$lote->deposito = Auth::user()->deposito;
			$lote->save();
		}

		if( (!isset($insumo['lote']) || empty($insumo['lote'])) ){

			$lote = Lote::where('insumo', $insumo['id'])
							   ->where('codigo','S/L')
							   ->where('deposito', Auth::user()->deposito)
							   ->orderBy('id', 'desc')
							   ->first();

			$lote->cantidad += $insumo['cantidad'];
			$lote->save();

			return;
		}

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
	 * Elimina todos los lotes de los insumos que se pasen.
	 *
	 * @param array $insumos
	 */
	public function deleteAll($insumos){

		$insumos = collect($insumos)->pluck('id');

		Lote::whereIn('insumo', $insumos)
			->where('deposito', Auth::user()->deposito)
			->delete();
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

			if(!isset($insumo['lote']) || empty($insumo['lote']))
				continue;

            if(isset($insumo['fecha']) && !empty($insumo['fecha'])){

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

            if(!isset($insumo['lote']) || empty($insumo['lote'])){
            	continue;
            }
            else{
	            foreach( $insumos as $key_validate => $insumo_validate){

	                if($key == $key_validate || !isset($insumo_validate['lote']) )
	                    continue;

	                if($insumo_validate['id'] == $insumo['id']){
	                    if($insumo_validate['lote'] == $insumo['lote'])
	                    	array_push($errores, $insumo['id']);
	                }
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

	 		if(!isset($insumo['lote']) || empty($insumo['lote']))
	 			continue;

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

	 		if(!isset($insumo['lote']) || empty($insumo['lote']))
	 			continue;

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
					  ->orderBy('vencimiento')
					  ->orderBy('id')
					  ->get();

		return $lotes;
	}

	/**
	 * Calcula los movimientos de los lotes de un insumo utilizando
	 * FEFO (First Expire First Out)
	 *
	 * @param array $insumos
	 * @return array
	 */
	private function fefo($insumo, $insumoMovimientos){

		$lotes = Lote::where('insumo', $insumo)
						    ->where('deposito', Auth::user()->deposito)
						    ->where('cantidad', '>', 0)
						    ->orderBy('vencimiento')
						    ->orderBy('id')
						    ->get();
		$movimientos  = [];

		$withLotes = $this->filterInsumosLote($insumoMovimientos);
		$withoutLotes = $this->filterInsumosLote($insumoMovimientos,false);


		if( !empty($withLotes) ){

			foreach ($withLotes as $movimiento) {

				$lotes = $lotes->each(function ($lote) use ($movimiento){

				    if ($lote->codigo == $movimiento['lote']) {
				        $lote->cantidad -= $movimiento['despachado'];
				    	false;
				    }
				});
			}

			$movimientos = $withLotes;
		}

		foreach ($withoutLotes as $movimiento)
		{
			$cantidad = $movimiento['despachado'];

			while( $cantidad > 0){

				$lote = $lotes->first(function ($key,$lote) {
				    return $lote['cantidad'] > 0;
				});

				if( $lote->cantidad < $cantidad ){

					$cantidad -= $lote->cantidad;
					$saldo = $lote->cantidad;
					$lote->cantidad = 0;
				}
				else{

					$lote->cantidad -= $cantidad;
					$saldo = $cantidad;
					$cantidad = 0;
				}

				$movimiento['lote'] = $lote->codigo;
				$movimiento['despachado'] = $saldo;

				array_push($movimientos, $movimiento);
			}
		}

		return $movimientos;
	}

	/**
	 * Verifica si un insumo tiene lotes asociados
	 *
	 * @return bool
	 */
	public function hasLote($insumo){

		$lotes =  Lote::where('insumo', $insumo['id'])
				  ->where('cantidad', '>', 0)
				  ->where('deposito', Auth::user()->deposito)
				  ->first();

		return (bool) $lotes;
	}

	/**
	 * Calcula, agrupa y devuelve, todos los movimientos de los insumos
	 * que se pasen.
	 *
	 * @param array $insumos
	 * @return arrray $groups
	 */
	public function calculaMovimientos($insumos){

		$movimientos = [];
		$groups = [];

		//Calcula los lotes de los insumos a realizar movimientos utilizando el metodo fefo,
		//Si este posee lotes en los registros y su lote no ha sido especificado.
		foreach ($insumos as $insumo) {

			if( array_search($insumo['id'], array_column($movimientos, 'id')) !== false )
				continue;

			if( $this->hasLote($insumo) ){

				$insumoMovimientos = array_filter($insumos, function($element) use ($insumo){
					return $element['id'] == $insumo['id'];
				});

				if( !empty($this->filterInsumosLote($insumoMovimientos, false)) ){
					$insumoMovimientos = $this->fefo($insumo['id'], $insumoMovimientos);
				}

				$movimientos = array_merge($movimientos, $insumoMovimientos);
			}
			else{
				array_push($movimientos, $insumo);
			}
		}

		//Agrupa los lotes de los movimientos de insumos.
		foreach ($movimientos as $movimiento){

			if(isset($movimiento['lote']) && !empty($movimiento['lote'])){

				$calculado = array_filter($groups, function($element) use ($movimiento){
	  				return $element['id'] == $movimiento['id'] && $element['lote'] == $movimiento['lote'];
				});

				if( !empty($calculado) )
					continue;

				$lotes = array_filter($movimientos, function($element) use ($movimiento){
	  				return $element['id'] == $movimiento['id'] && $element['lote'] == $movimiento['lote'];
				});

				$despachado = 0;
				$solicitado = 0;

				foreach ($lotes as $lote) {
					$despachado += $lote['despachado'];
					$solicitado += $lote['solicitado'];
				}

				$movimiento['despachado'] = $despachado;
				$movimiento['solicitado'] = $solicitado;
			}

			array_push($groups, $movimiento);
		}

		//Devuelve un arreglo con todos los movimientos de insumos a realizar
		//con lotes espeficicados y cantidades. Nota: Si no pose lote un movimiento de insumo en el arreglo
		//sera debido a que no posee lotes en los registros de lotes.
		return $groups;
	}

	/**
	 * Devuelve un sub-arreglo del arreglo de insumos que se pase
	 * conteniendo los insumos con lotes (por defecto) o si se pasa  FALSE
	 * los insumos sin lotes
	 *
	 * @param array $insumos
	 * @param bool $filter
	 */
	public function filterInsumosLote($insumos,$filter=true){

		$insumosFiltrados = [];

		if($filter){

			$insumosFiltrados = array_filter($insumos, function($element){
	  			return isset($element['lote']) && !empty($element['lote']);
			});
		}
		else{

			$insumosFiltrados = array_filter($insumos, function($element){
	  			return !isset($element['lote']) || empty($element['lote']);
			});
		}

		return $insumosFiltrados;
	}
}
