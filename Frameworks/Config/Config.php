<?php

namespace PAO\Config;

use ArrayAccess;
use PAO\Application;
use PAO\Exception\SystemException;
use Illuminate\Contracts\Config\Repository;

class Config implements ArrayAccess, Repository
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var string
     */
    private $file;

    /**
     * @var array
     */
    private $config = array();

    /**
     * Config constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->file =  DIR.'/Config/config.ini';
        $this->parseConfig();
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return \Arr::has($this->config, $key);
    }

    /**
     * Get the specified configuration value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key = null, $default = null)
    {
        return \Arr::get($this->config, $key, $default);
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

                \Arr::set($this->config, $innerKey, $innerValue);
            }
        } else {
            \Arr::set($this->config, $key, $value);
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
        return $this->config;
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
     * @return bool
     * @throws SystemException
     */
    public function parseConfig()
    {
        $env = getenv('ENV');

        if(!$env && is_file($config = PAO.'/RunTime/Cache/config.cache.php')){
            return $this->config = require($config);
        }

        if(!is_readable($this->file)){
            throw new SystemException('unable load config file from '.$this->file);
        }

        /**
         * load system config
         */
        $config = \Arr::dot(parse_ini_file($this->file, true));

        /**
         * load environment config
         */
        if($env && is_file($config_env = dirname($this->file).'/config.'.$env.'.ini')){
            $config = array_replace_recursive($config,\Arr::dot(parse_ini_file($config_env, true)));
        }

        $this->set($config);

        file_put_contents(PAO.'/RunTime/Cache/config.cache.php', '<?php return '.str_replace(array(PHP_EOL,' '),'',var_export($this->all(), true)).';');

        return true;
    }
}
