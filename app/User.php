<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Privilegio;
use App\Permissions_assigned;
use App\Permission;
use App\Deposito;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword;
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'apellido', 'cedula', 'rol', 'rango', 'email', 'password', 'deposito', 'rol'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token','created_at' , 'updated_at', 'deleted_at'];

    public function hasPermissions($permissions,$all=false){


      if( $this->id  == 1)
        return true;


      if(!$all){
        foreach ($permissions as $permission){
          $idP  = Permission::where('ip', $permission)->value('id');
          if(Permissions_assigned::where('role', $this->rol)->where('permission', $idP)->first())
            return true;
        }

        return false;
      }
      else{
        foreach ($permissions as $permission){
          $idP  = Permission::where('ip', $permission)->value('id');
          if(!Permissions_assigned::where('role', $this->rol)->where('permission', $idP)->first())
            return false;
        }

        return true;
      }
    }

	public function getDepositoName()
	{
		return  Deposito::where('id', $this->deposito)->value('nombre'); 
	}
}
