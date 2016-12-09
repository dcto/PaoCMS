<?php

namespace PAO\I18n;


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
	protected $item;


	/**
	 * 初始化语言对象
	 * @param [type] $items [预加载语言]
	 */
	public function __construct( $items = [] )
	{
        $this->item = $items;

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
        $lang = \Arr::get($this->item, $key);
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
                \Arr::set($this->item, $innerKey, $innerValue);
            }
        } else {
            \Arr::set($this->item, $key, $value);
        }
    }

    /**
     * Get all of the language items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->item;
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
        $this->parseLanguage();
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
            throw new \InvalidArgumentException($lang.' The language arguments count ['.implode(',', $replace).'] not match.');
        }
        return vsprintf($lang, $replace);
    }

    /**
     * Parse Language
     *
     * @return bool
     */
    private function parseLanguage()
    {
        $dir = path(config('dir.lang'));

        $appLang = rtrim($dir,'/').'/'.$this->lang.'.ini';
        $subLang = rtrim($dir,'/').'/'.APP.'/'.$this->lang.'.ini';
        $language = \Arr::dot(parse_ini_file($appLang, true));

        if(is_readable($subLang)){
            $language = array_replace_recursive($language,\Arr::dot(parse_ini_file($subLang, true)));
        }
        return $this->set($language);
    }
}
