<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'vencimiento'];

    protected $date = [
        'created_at',
        'updated_at',
        'vencimiento',
    ];

    /**
     * Filtra los lostes mayores que cero
     *
     * @param $query
     * @return mixed
     */
    public function scopeWithExistence($query)
    {
       return $query->where('cantidad', '>', 0);
    }
}
