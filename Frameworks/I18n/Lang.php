<?php

namespace PAO\I18n;


use PAO\Exception\NotFoundException;

class Lang
{
	/**
	 * 当前设定语言
	 * @var [type]
	 */
	protected $lang = null;

	/**
	 * 语言配置器
	 * @var array
	 */
	protected $item = array();


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
        if(!defined('ENV') && is_file($lang = PAO.'/RunTime/Cache/Language/'.$this->lang.'.cache.php')){
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
}
