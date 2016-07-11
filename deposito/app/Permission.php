<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
  protected $fillable = ['nombre', 'modulo', 'ip'];
  protected $hidden   = ['created_at' , 'updated_at'];
}
