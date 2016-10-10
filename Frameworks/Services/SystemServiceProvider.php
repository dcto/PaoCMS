<?php

namespace PAO\Services;

use PAO\Exception\SystemException;
use Illuminate\Support\ServiceProvider;


class SystemServiceProvider extends ServiceProvider
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
        $router = APP . '/Router/'.NAME.'.php';
        if(is_readable($router)){
            require($router);
        }else{
            throw new SystemException('Undefined router file in the App Path '. $router);
        }

        /**
         * 加载App function
         */
        if(is_readable($function = APP.'/Helper/helper.php')){
            require($function);
        }
        if($functions = config('helper')){
            $functions = array_unique($functions);
            foreach ($functions as $function){
                if(is_readable($function = APP.'/Helper/'.$function)){
                    require($function);
                }
            }
        }
    }
}