<?php
/**
 * Created by PhpStorm.
 * User: bybcr
 * Date: 15.4.2017
 * Time: 01:08
 */

namespace murataygun;


use Validator;
use Illuminate\Support\ServiceProvider;
use murataygun\TcKimlik;


class TCKimlikServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('tckimlik', function($attribute, $value, $parameters, $validator) {
            return TcKimlik::confirm($value);
        });
        Validator::replacer('tckimlik', function($message, $attribute, $rule, $parameters) {
            if($message=="validation.tckimlik") return "Belirtilen T.C. Kimlik Numarası doğrulanamadı.";
            return $message;
        });
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}