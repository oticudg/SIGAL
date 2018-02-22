<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insumo extends Model
{	
	use SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $fillable = ['codigo','descripcion'];
   	protected $hidden   = ['created_at' , 'updated_at','deleted_at'];

   	public function lotes()
    {
        return $this->hasMany('App\Lote', 'insumo', 'id');
    }


    /**
     * Regresa todos los lotes de un insumo filtrados por el ID del
     * deposito que se pase como argumento
     *
     * @param Int $deposito
     */
    public function getLotesbyDeposito($deposito)
    {
        return $this->lotes()->where('deposito', $deposito);
    }
}
