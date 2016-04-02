<?php

namespace PAO;

use ArrayAccess;
use Illuminate\Support\Arr;
use Illuminate\Container\Container;
use PAO\Exception\SystemException;

class Translator
{
	/**
	 * 容器
	 * @var [type]
	 */
	protected $container;

	/**
	 * 当前设定语言
	 * @var [type]
	 */
	protected $language;

	/**
	 * 语言配置器
	 * @var [type]
	 */
	protected $items;


	/**
	 * 初始化语言对象
	 * @param [type] $items [预加载语言]
	 */
	public function __construct( $items = null)
	{
		$this->container = Container::getInstance();

		$language = $this->container->config('config.language');

        $PaoLanguage = PAO.DIRECTORY_SEPARATOR.'Language'.DIRECTORY_SEPARATOR.$language.'.ini';

        $AppLanguage = PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'Language'.DIRECTORY_SEPARATOR.$language.'.ini';

        $readable = false;

        if(is_readable($PaoLanguage))
        {
        	$this->items = (array) parse_ini_file($PaoLanguage, true);

        	$readable = true;
		}

        if(is_readable($AppLanguage))
        {
            $this->items = array_replace_recursive($this->items, (array) parse_ini_file($AppLanguage, true));

            $readable = true;
        }

        if(!$readable) throw new SystemException('The ['.$language.'] language was not readable!');


        if(is_array($items))
        {
        	$this->items = array_replace_recursive($this->items, $items);
        }
	}

    /**
     * Determine if the given Languageuration option exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Get the specified Languageuration value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
	public function get($key, $default = null)
	{
        $lang = Arr::get($this->items, $key, $default);
        if(is_string($lang)){
            return $lang;
        }
        return $default ?: $key;
	}

    /**
     * Set a given Languageuration value.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                Arr::set($this->items, $innerKey, $innerValue);
            }
        } else {
            Arr::set($this->items, $key, $value);
        }
    }

    /**
     * Get all of the Languageuration items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

}
