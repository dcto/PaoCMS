<?php

namespace PAO\Config;

use ArrayAccess;
use PAO\Application;
use Illuminate\Contracts\Config\Repository;
use PAO\Exception\SystemException;

class Config implements ArrayAccess, Repository
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var string
     */
    private $config;

    /**
     * Config constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->config =  DIR.'/Config/config.ini';
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return \Arr::has($this->app->config, $key);
    }

    /**
     * Get the specified configuration value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        /*
        if(!$this->has($key))
        {
            $this->load($key);
        }*/
        return \Arr::get($this->app->config, $key, $default);
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {

                \Arr::set($this->app->config, $innerKey, $innerValue);
            }
        } else {
            \Arr::set($this->app->config, $key, $value);
        }
    }

    /**
     * Prepend a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function prepend($key, $value)
    {
        $array = $this->get($key);

        array_unshift($array, $value);

        $this->set($key, $array);
    }

    /**
     * Push a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function push($key, $value)
    {
        $array = $this->get($key);

        $array[] = $value;

        $this->set($key, $array);
    }

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->app->config;
    }

    /**
     * Determine if the given configuration option exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get a configuration option.
     *
     * @param  string  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Set a configuration option.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Unset a configuration option.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->set($key, null);
    }


    /**
     * [parseConfig 解析配置文件]
     * @return array
     * @throws SystemException
     */
    public function parseConfig()
    {

        if(!is_readable($this->config)){
            throw new SystemException('unable load config file from '.$this->config);
        }

        /**
         * load system config
         */
        $config = \Arr::dot(parse_ini_file($this->config, true));

        /**
         * load environment config
         */
        if(is_readable($config_env = dirname($this->config).'/config.'.ENV.'.ini')){
            $config = array_replace_recursive($config,\Arr::dot(parse_ini_file($config_env, true)));
        }

        return $this->set($config);
    }

    /**
     * [load load config file]
     *
     * @param $name
     * @return array
     * @author 11.
     */
    /*
    private function load($key)
    {
        $key = trim($key,'.');
        $name = trim(strstr($key,'.') ? strstr($key, '.', true) : $key);
        $PaoConfig = PAO.DIRECTORY_SEPARATOR.'.'.$name;
        $AppConfig = DIR.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.$name.'.php';

        if(is_readable($AppConfig)) require($AppConfig);
        if(is_readable($PaoConfig)) require($PaoConfig);

        if(isset($$name)){
            $this->set($name, $$name);
            return $$name;
        }else{
            return array();
        }
    }
    */
}
