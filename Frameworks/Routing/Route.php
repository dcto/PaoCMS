<?php
namespace PAO\Routing;

use PAO\Exception\NotFoundHttpException;
use PAO\Support\Arr;

/**
 * The Route class is responsible for routing an HTTP request to an assigned Callback function.
 */
class Route
{
    /**
     * @var string tag
     */
    public $tag;

    /**
     * @var string current request url
     */
    public $url;

    /**
     * @var string
     */
    public $name;

    /**
     * @var $hidden boolean
     */
    public $menu;

    /**
     * @var string current route language
     */
    public $lang;

    /**
     * @var string route icon for menu
     */
    public $icon;

    /**
     * @var string current route path;
     */
    public $route;

    /**
     * @var string Matching regular expression
     */
    public $regex;

    /**
     * @var string group name
     */
    public $group;

    /**
     * @var string The matched HTTP method
     */
    public $method;

    /**
     * @var array Supported HTTP methods
     */
    public $methods;

    /**
     * @var string current route callable
     */
    public $callable;

    /**
     * @var string callable namespace
     */
    public $namespace;

    /**
     * @var array http request parameters
     */
    public $parameters = array();


    /**
     * Constructor.
     *
     * @param string|array $method HTTP method(s)
     * @param string $pattern URL pattern
     * @param string|array $property Callback function or options
     */
    public function __construct($method, $route, $property = array())
    {
        $this->tag = Arr::get($property, 'tag');

        $this->name = Arr::get($property, 'name', $this->tag);

        $this->lang = Arr::get($property, 'lang')?:Arr::get(Arr::get($property, 'group'),'lang');

        $this->menu = Arr::get($property, 'menu')?:Arr::get(Arr::get($property, 'group'),'menu');

        $this->route = $route;

        $this->regex =  Arr::get($property, 'regex', $this->route);

        $this->group =  Arr::get(Arr::get($property, 'group'),'tag');

        $this->methods = array_map('strtoupper', is_array($method) ? $method : array($method));

        $this->callable = Arr::get($property, 'call');

        $this->namespace = Arr::get($property, 'namespace')?:Arr::get(Arr::get($property, 'group'),'namespace');

        if (in_array('GET', $this->methods) && !in_array('HEAD', $this->methods)) {
            $this->methods[] = 'HEAD';
        }
    }

    public function __call($property, $arguments)
    {
        if (!property_exists($this, $property)) {
            throw new  NotFoundHttpException('The route property no available of to the ' . $property . ' action');
        }

        if ($arguments) {
            $this->$property = $arguments[0];
            return $this;
        }
        return $this->$property;
    }


    /**
     * get route callable
     *
     * @return string
     */
    public function getCallable()
    {
        return rtrim($this->namespace,'\\') .'\\' .ltrim($this->callable, '\\');
    }
}
