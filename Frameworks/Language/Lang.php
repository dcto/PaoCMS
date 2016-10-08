<?php

namespace PAO\Language;


use PAO\Exception\SystemException;

class Lang
{
	/**
	 * 当前设定语言
	 * @var [type]
	 */
	protected $lang;

	/**
	 * 语言配置器
	 * @var [type]
	 */
	protected $items;


	/**
	 * 初始化语言对象
	 * @param [type] $items [预加载语言]
	 */
	public function __construct( $items = [] )
	{
        $this->items = $items;

		$this->lang = config('app.language');

        $this->parseLanguage();
	}


    /**
     * Get the specified language value.
     * @return mixed|string
     */
	public function get()
	{
        $args = func_get_args();
        $key = array_shift($args);
        $lang = \Arr::get($this->items, $key);
        if(is_string($lang)){
            return $args ? $this->replacements($lang, $args) : $lang;
        }
        return $key;
	}

    /**
     * Set a given language value.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $innerKey => $innerValue) {
                \Arr::set($this->items, $innerKey, $innerValue);
            }
        } else {
            \Arr::set($this->items, $key, $value);
        }
    }

    /**
     * Get all of the language items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * get current language
     *
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * set current language
     *
     * @param $language string
     */
    public function setLang($language)
    {
        $this->lang = $language;
        $this->parseLanguage($language);
    }

    /**
     * Make the place-holder replacements on a line.
     *
     * @param  string  $line
     * @param  array   $replace
     * @return string
     */
    private function replacements($lang, array $replace)
    {
        if(substr_count($lang,'%s') > sizeof($replace)){
            throw new SystemException($lang.' The language arguments count ['.implode(',', $replace).'] not match.');
        }
        return vsprintf($lang, $replace);
    }

    /**
     * @param $language
     * @throws SystemException
     */
    private function parseLanguage($lang = null)
    {

        $lang = $this->lang = $lang?:$this->lang;

        $AppLanguage = APP.DIRECTORY_SEPARATOR.'Lang'.DIRECTORY_SEPARATOR.$lang.'.ini';

        $SubLanguage = APP.DIRECTORY_SEPARATOR.'Lang'.DIRECTORY_SEPARATOR.NAME.DIRECTORY_SEPARATOR.$lang.'.ini';

        if(is_readable($AppLanguage)){
            $this->items = (array) parse_ini_file($AppLanguage, true);
        }
        if(is_readable($SubLanguage)) {
            $this->items = array_replace_recursive($this->items, (array) parse_ini_file($SubLanguage, true));
        }
    }
}
