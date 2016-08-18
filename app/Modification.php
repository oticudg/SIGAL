<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modification extends Model
{
    protected $fillable = ['movimiento', 'naturaleza', 'original_documento',
                           'original_tercero', 'updated_documento', 'updated_tercero', 'deposito'];
}
