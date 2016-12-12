<?php

namespace App\Repositories;

use Auth;
use App\Lote;
use App\Inventario;
use DB;

class AlertsRepository
{
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
                            ->first();

                if($lotes)
                    return true; 

            }
        }

        return false;
    } 
}