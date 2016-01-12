<?php

namespace PAO;

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


	public function __construct()
	{
		$this->container = Container::getInstance();

		$language = $this->container->config('config.language');

		$this->language = APP. DIRECTORY_SEPARATOR. 'Language'.DIRECTORY_SEPARATOR.$language;

		if(!is_dir($this->language)) throw new SystemException('The ['.$language.'] was not available');
	}



}
