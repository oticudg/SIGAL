<?php

namespace App\Providers;

use Validator;
use App\Insumo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Custom validation rules 
         */

        Validator::extend('alpha_spaces', function($attribute, $value)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('cedula', function($attribute, $value)
        {
            return preg_match('/^([0-9]{6,8})$/', $value);
        });

        Validator::extend('rif', function($attribute, $value)
        {
            return preg_match('/^([J,G]-([0-9]{8,9})-[0-9])$/', $value);
        });
        
        Validator::extend('insumos', function($attribute, $value)
        {   
            if( empty($value) || !is_array($value)){
                return false; 
            }
            else{

                foreach ($value as $insumo){
                    if( !isset($insumo['cantidad']) || !isset($insumo['id']) || $insumo['cantidad'] <= 0
                        || !is_int($insumo['cantidad']) || !Insumo::where('id',$insumo['id'])->first())

                        return false;
                }
            }
            
            return true;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
