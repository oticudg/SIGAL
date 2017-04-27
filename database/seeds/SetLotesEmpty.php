<?php

use Illuminate\Database\Seeder;
use App\Lote;

class SetLotesEmpty extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 		DB::table('inventarios')
 		  ->whereBetween('updated_at',['2017-04-21 00:00:00', '2017-04-26 23:00:00'])
 		  ->chunk(100,function($inventarios){
 		  		foreach ($inventarios as $inventario) {

 		  			$cantidad = DB::table('lotes')
			 		  			  ->where('insumo', $inventario->insumo)
			 		  			  ->where('deposito', $inventario->deposito)
			 		  			  ->where('codigo', '!=', 'SIN LOTES')
			 		  			  ->sum('cantidad');
			 		
			 		$cantidad =  $cantidad ? $cantidad :0;


		 			$lote = Lote::where('insumo', $inventario->insumo)
		 							  ->where('codigo', 'SIN LOTES')
		 							  ->where('deposito', $inventario->deposito)
		 							  ->first();
	
		 			if(!$lote)
		 				continue;
		 			
		 			$lote->codigo ='S/L';
		 			$lote->cantidad = $inventario->existencia - $cantidad;
		 			$lote->save();
 		  		}
 		  });

    }
}
