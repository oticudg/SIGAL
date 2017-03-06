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
     * Devuelve los insumos que se encuentran en alerta segun su 
     * fecha de vencimiento proximos a vencer o vencidos.
     *
     * @return Illuminate\Database\Eloquent\Collection $insumos
     */
    public function insumosVencimiento(){

        $vencidos = Lote::where('cantidad', '>', 0)
                    ->where('vencimiento', '<>', '')
                    ->where('deposito', Auth::user()->deposito)
                    ->where('vencimiento', '<=', DB::raw('CURDATE()'))
                    ->join('insumos', 'insumos.id', '=', 'lotes.insumo')
                    ->select('lotes.id','insumos.descripcion', 'insumos.codigo', 'lotes.codigo as lote', 'lotes.cantidad', DB::raw('DATE_FORMAT(vencimiento, "%d/%m/%Y") as fecha'), DB::raw('"danger" as type'));

        $lotes = Lote::where('cantidad', '>', 0)
                    ->where('vencimiento', '<>', '')
                    ->where('deposito', Auth::user()->deposito)
                    ->where('vencimiento', '>', DB::raw('CURDATE()'))
                    ->where('vencimiento', '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL 1 MONTH)'))
                    ->join('insumos', 'insumos.id', '=', 'lotes.insumo')
                    ->select('lotes.id','insumos.descripcion', 'insumos.codigo', 'lotes.codigo as lote', 'lotes.cantidad', DB::raw('DATE_FORMAT(vencimiento, "%d/%m/%Y") as fecha'), DB::raw('"warning" as type'))
                    ->union($vencidos)
                    ->orderBy('id', 'desc')
                    ->get();

        return $lotes;
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

        $cantidad = 0;
        foreach ($registros as $registro) {

            if( $registro['existencia'] <= $registro['Cmed'] || $registro['existencia'] <= $registro['Cmin']){
                
                $cantidad++;
            }

           /* else{

                $lotes = Lote::where('insumo', $registro->insumo) 
                            ->where('cantidad', '>', 0)
                            ->where('vencimiento', '<>', '')
                            ->where('deposito', $deposito)
                            ->where('vencimiento', '<=', DB::raw('DATE_ADD(CURDATE(), INTERVAL 1 MONTH)'))
                            ->get();

                if($lotes)
                    return true; 

            }*/
        }

        return $cantidad;
       // return false;
    } 
}