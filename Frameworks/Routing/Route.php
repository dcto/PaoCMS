<?php
namespace PAO\Routing;

use PAO\Exception\NotFoundHttpException;
use PAO\Support\Arr;

/**
 * The Route class is responsible for routing an HTTP request to an assigned Callback function.
 */
class Route
{

    public $name;

    /**
     * @var string The current route hash
     */
    public $hash;

    /**
     * @var string The current matched PathInfo
     */
    public $path;

    /**
     * @var string current route language
     */
    public $lang;

    /**
     * @var menu flag
     */
    public $menu;

    /**
     * @var string name
     */
    public $alias;

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
     * @var string URL pattern
     */
    public $pattern;

    /**
     * @var string current route callable
     */
    public $callable;

    /**
     * @var route priority
     */
    public $priority = 0;

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
    public function __construct($method, $pattern, $property = array())
    {
        $this->name = Arr::get($property,'name', $this->hash);

        $this->alias = Arr::get($property, 'as');

        $this->lang = Arr::get($property,'lang');

        $this->group = $property['group'];

        $this->methods = array_map('strtoupper', is_array($method) ? $method : array($method));

        $this->pattern = $pattern ?: '/';

        $this->callable = Arr::get($property,'to');

        if (isset($property['prefix'])) {
            $this->prefix($property['prefix']);
        }
        if (in_array('GET', $this->methods) && ! in_array('HEAD', $this->methods)) {
            $this->methods[] = 'HEAD';
        }
    }

    public function __call($property, $arguments)
    {
        if(!property_exists($this, $property)){
            throw new  NotFoundHttpException('The route property no available of to the '.$property. ' action');
        }

        if($arguments[0]){
            $this->$property = $arguments[0];
            return $this;
        }
        return $this->$property;
    }


    /**
     * Add a prefix to the route URI.
     *
     * @param  string  $prefix
     * @return \PAO\Routing\Route
     */
    private function prefix($prefix)
    {
        $this->pattern = trim($prefix, '/') .'/' .trim($this->pattern, '/');

        return $this;
    }
}
