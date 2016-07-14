<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissions_assigned extends Model
{
  protected $fillable = ['role', 'permission'];
  protected $hidden   = ['created_at' , 'updated_at'];
}
