<?php

namespace PAO\Services;

use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * 注册路由
         */
        $router = DIR . '/Router/'.APP.'.php';
        if(is_readable($router)){
            require($router);
        }else{
            throw new \InvalidArgumentException('Undefined router file in the App Path '. $router);
        }

        /**
         * 加载App function
         */
        if(is_readable($function = DIR.'/Helper/helper.php')){
            require($function);
        }
        if($functions = config('helper')){
            $functions = array_unique($functions);
            foreach ($functions as $function){
                if(is_readable($function = DIR.'/Helper/'.$function)){
                    require($function);
                }
            }
        }
    }
}