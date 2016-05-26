<?php

namespace App\Providers;

use Validator;
use Input;
use App\Insumo;
use App\Entrada;
use App\Salida;
use App\Deposito;
use App\Provedore;
use App\Departamento;
use App\Insumos_entrada;
use App\Insumos_salida;
use Illuminate\Support\ServiceProvider;
use App\Documento;

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
            return preg_match('/^([J,G,N]-([0-9]{8,12}))$/', $value);
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

        Validator::extend('diff_departamento', function($attribute, $value, $parameters)
        {
            $id = Input::get($parameters[0]);
            $salida = Salida::where('id', $id)->value('departamento');

            if( $value == $salida)
                return false;

            return true;

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

        Validator::extend('insumos_validate_e', function($attribute, $value)
        {
            foreach ($value as $insumo){

                if(!isset($insumo['cantidad']))
                    continue;

                $originalI = Insumos_entrada::where('id',$insumo['id'])->first();

                if( !isset($insumo['id']) || !$originalI ||
                    $originalI['cantidad'] == $insumo['cantidad'] ||
                    !is_int($insumo['cantidad']) || $insumo['cantidad'] < 0)
                    return false;
            }

            return true;
        });

        Validator::extend('insumos_validate_s', function($attribute, $value)
        {
            foreach ($value as $insumo){

                if(!isset($insumo['despachado']))
                    continue;

                $originalI = insumos_salida::where('id',$insumo['id'])->first();

                if( !isset($insumo['id']) || ! $originalI ||
                    $insumo['despachado'] == $originalI['despachado'] ||
                    !is_int($insumo['despachado']) || $insumo['despachado'] < 0)
                    return false;

                if( !isset( $insumo['solicitado'] ) ){

                    if( $originalI['solicitado'] < $insumo['despachado'])
                        return false;
                }
                else{

                    if( !is_int($insumo['solicitado']) || $insumo['solicitado'] < 0 ||
                        $insumo['solicitado'] == $originalI['solicitado'])
                        return false;
                }
            }

            return true;
        });

        Validator::extend('one_insumo_entrada', function($attribute, $value, $parameters)
        {
            $entrada = Input::get($parameters[0]);
            $insumos = Insumos_entrada::where('entrada', $entrada)->get();

            if($insumos->count() == 1 && isset($value[0]['cantidad']) && $value[0]['cantidad'] == 0)
                return false;

            return true;

        });

        Validator::extend('one_insumo_salida', function($attribute, $value, $parameters)
        {
            $salida = Input::get($parameters[0]);
            $insumos = Insumos_salida::where('salida', $salida)->get();

            if($insumos->count() == 1 && isset($value[0]['despachado']) && $value[0]['despachado'] == 0)
                return false;

            return true;

        });


        Validator::extend('insumo', function($attribute, $value)
        {
            if( !Insumo::where('id', $value)->first())
                return false;

            return true;
        });

        Validator::extend('deposito', function($attribute, $value)
        {
            if( !Deposito::where('id', $value)->first() )
                return false;

            return true;
        });

        Validator::extend('date_limit_current', function($attribute, $value)
        {
            $value = str_replace('/','-',$value);

            if(strtotime($value) > strtotime(date("Y-m-d")) )
                return false;

            return true;
        });

        Validator::extend('insumos_ids_array', function($attribute, $value)
        {
            if(!is_array($value))
                return false;

            foreach ($value as $v) {
              if(!insumos_salida::where('id',$v)->first())
                return false;
            }

            return true;
        });

        Validator::extend('documento_salida', function($attribute, $value)
        {
            $documento = Documento::where('id', $value)->first();

            if(!$documento)
              return false;

            return $documento->naturaleza == 'salida';

        });

        Validator::extend('tercero', function($attribute, $value, $parameters)
        {
            $documento = Input::get($parameters[0]);
            $tipo      = Documento::where('id', $documento)->value('tipo');

            if($tipo == 'interno')
              return true;

            if($tipo == 'proveedor'){
              return Provedore::where('id', $value)->first();
            }

            if($tipo == 'servicio'){
              return Departamento::where('id', $value)->first();
            }

            if($tipo == 'deposito'){
              return Deposito::where('id', $value)->first();
            }

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
