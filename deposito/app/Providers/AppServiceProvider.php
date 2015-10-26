<?php

namespace App\Providers;

use Validator;
use Input;
use App\Insumo;
use App\Entrada;
use App\Insumos_entrada;
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

        Validator::extend('equal_provedor', function($attribute, $value, $parameters)
        {   
            $orden = Input::get($parameters[0]);
            $entrada  = Entrada::where('orden', $orden)->value('provedor'); 

            if(!$entrada){
                return true;
            }
            else{
                
                if( $value != $entrada)
                    return false;

                return true;
            }

        });
        
        Validator::extend('diff_provedor', function($attribute, $value, $parameters)
        {   
            if( empty($value) ){
                return true;                
            }
            else{

                $id = Input::get($parameters[0]);    
                $entrada = Entrada::where('id', $id)->value('provedor');

                if( $value == $entrada)
                    return false;

                return true;
            }
        });

        Validator::extend('diff_orden', function($attribute, $value, $parameters)
        {   
            if( empty($value) ){
                return true;                
            }
            else{

                $id = Input::get($parameters[0]);    
                $entrada = Entrada::where('id', $id)->value('orden');

                if( $value == $entrada)
                    return false;

                return true;
            }
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

        Validator::extend('insumos_salida', function($attribute, $value)
        {   
            if( empty($value) || !is_array($value)){
                return false; 
            }
            else{

                foreach ($value as $insumo){
                    if( !isset($insumo['solicitado']) || !isset($insumo['despachado']) || 
                        !isset($insumo['id']) || $insumo['solicitado'] <= 0 ||
                        $insumo['despachado'] <= 0 || $insumo['solicitado'] < $insumo['despachado'] ||
                        !is_int($insumo['solicitado']) || !is_int($insumo['despachado']) ||  
                        !Insumo::where('id',$insumo['id'])->first())

                        return false;
                }
            }
            
            return true;
        });

        Validator::extend('insumos_alarmas', function($attribute, $value)
        {   
            if( empty($value) || !is_array($value)){
                return false; 
            }
            else{

                foreach ($value as $insumo){
                    if( !isset($insumo['id']) || !isset($insumo['min']) || 
                        !isset($insumo['med']))
                        return false;

                    if($insumo['min'] <= 0 || $insumo['med'] <= 0 || 
                        $insumo['min'] >= $insumo['med'])
                           return false;
                }
            }
            
            return true;
        });

        Validator::extend('insumos_validate', function($attribute, $value)
        {           
            foreach ($value as $insumo){

                if(!isset($insumo['cantidad']))
                    continue;

                if( !isset($insumo['id']) || !Insumos_entrada::where('id',$insumo['id'])->first() ||
                    !is_int($insumo['cantidad']) || $insumo['cantidad'] < 0)  
                    return false; 
            }
            
            return true;
        });

        Validator::extend('one_insumo', function($attribute, $value, $parameters)
        {   
            $entrada = Input::get($parameters[0]);
            $insumos = Insumos_entrada::where('entrada', $entrada)->get(); 

            if($insumos->count() == 1 && isset($value[0]['cantidad']) && $value[0]['cantidad'] == 0)
                return false;

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
