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
}
