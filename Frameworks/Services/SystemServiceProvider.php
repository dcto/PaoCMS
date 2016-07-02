<?php

namespace PAO\Services;

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
         * 加载系统function
         */
        require (dirname(__DIR__).'/Function.php');
    }
}