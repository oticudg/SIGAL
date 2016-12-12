<?php

namespace App\Repositories;

use Auth;
use App\Lote;
use App\Inventario;
use DB;

class AlertsRepository
{

    /**
     * Devuelve los insumos que se encuentran en alerta segun su 
     * nivel critico o bajo en el inventario.
     *
     * @return Illuminate\Database\Eloquent\Collection $insumos
     */
    public function insumosNivel(){

        $deposito = Auth::user()->deposito;

        $registros = Inventario::where('deposito', $deposito)
                                 ->get(['id', 'existencia', 'Cmed', 'Cmin']);
        $ids = [];

        foreach ($registros as $registro) {
            if( $registro['existencia'] <= $registro['Cmed'] || $registro['existencia'] <= $registro['Cmin'])
                array_push($ids, $registro['id']);
        }

        $insumos = DB::table('insumos')
                   ->join('inventarios', 'insumos.id', '=', 'inventarios.insumo')
                   ->whereIn('inventarios.id', $ids)
                   ->select('inventarios.insumo as id','insumos.codigo','insumos.descripcion',
                    'inventarios.existencia','inventarios.Cmin as min', 'inventarios.Cmed as med')
                   ->get();

        return $insumos;
    }

    /**
     * Devuelve si hay alguna alerta en los insumos del inventario 
     *
     * @return bool  
     */
	public static function alert(){
        
        $deposito = Auth::user()->deposito;
    	$registros = Inventario::where('deposito', $deposito)
                                ->get(['id', 'existencia', 'insumo','Cmed', 'Cmin']);

        foreach ($registros as $registro) {

            if( $registro['existencia'] <= $registro['Cmed'] || $registro['existencia'] <= $registro['Cmin']){
                return true;
            }
            else{

                $lotes = Lote::where('insumo', $registro->insumo) 
                            ->where('cantidad', '>', 1)
                            ->where('vencimiento', '<>', '')
                            ->where('deposito', $deposito)
                            ->where('vencimiento', '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL 6 MONTH)'))
                            ->get();

                if($lotes)
                    return true; 

            }
        }

        return false;
    } 
}