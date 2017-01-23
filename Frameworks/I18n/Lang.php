<?php

namespace PAO\I18n;


use PAO\Exception\NotFoundException;

class Lang
{
	/**
	 * 当前设定语言
	 * @var [type]
	 */
	private $lang = null;

	/**
	 * 语言配置器
	 * @var array
	 */
	private $item = array();

    /**
     * 当前根键器
     * @var string
     */
    private $temp = null;

    /**
     * 语言选择器
     * @var array
     */
    private $keys = array();

    /**
     * 当前调用参数
     * @var array
     */
    private $args = array();

	/**
	 * 初始化语言对象
	 * @param [type] $items [预加载语言]
	 */
	public function __construct()
	{
		if($this->lang = make('request')->get('lang')){
            $this->setLang($this->lang);
        }else{
            if(!$this->lang = make('cookie')->get('PAO_LANG')){
                $this->setLang(config('app.language', function(){
                    throw new \InvalidArgumentException('Non set default app language in the config.ini');
                }));
            }
        }
        $this->parseLanguage();
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
     * Get the specified language value.
     * @return mixed|string
     */
    public function get()
    {
        $args = func_get_args();

        $key = array_shift($args);

        return $this->take($key, $args);
    }

    /**
     * take language
     *
     * @param $key
     * @param array $args
     * @return string
     */
    public function take($key, array $args = array())
    {
        if(is_string($lang = \Arr::get($this->item, $key))){
            return $args ? $this->replacements($lang, $args) : $lang;
        }
        return $key;
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
     * @param $lang string
     */
    public function setLang($lang = null)
    {
        if($lang){
            if(config('i18n.' .$lang)){
                make('cookie')->set('PAO_LANG', $lang, 31536000);
            }else{
                throw new NotFoundException( 'Invalid '. $lang .' language for this app');
            }
        }
        $this->lang = $lang;
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
        if(!getenv('ENV') && is_file($lang = PAO.'/RunTime/Cache/Language/'.$this->lang.'.cache.php')){
            $this->item = require($lang);
        }

        $dir = path(config('dir.lang'));

        $appLang = rtrim($dir,'/').'/'.$this->lang.'.ini';
        $subLang = rtrim($dir,'/').'/'.APP.'/'.$this->lang.'.ini';
        $language = \Arr::dot(parse_ini_file($appLang, true));

        if(is_readable($subLang)){
            $language = array_replace_recursive($language,\Arr::dot(parse_ini_file($subLang, true)));
        }

        $this->set($language);

        $cacheDir = path(config('dir.cache'), '/Language/').'/';

        if(!is_dir($cacheDir)){
            make('file')->mkDir($cacheDir);
        }
        file_put_contents($cacheDir.$this->lang.'.cache.php', '<?php return '.str_replace(array(PHP_EOL,' '),'',var_export($this->all(), true)).';');
        return true;
    }

    /**
     * 动态方法调用语言包
     * @param $name
     * @param $arguments
     * @return mixed|string
     */
    public function __call($key, $args)
    {
echo $key.'<br />';
        array_push($this->keys, $key);
        $this->args = $args;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->take(implode('.', $this->keys), $this->args);
    }
}
