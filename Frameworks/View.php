<?php

namespace PAO;

use Illuminate\Container\Container;
use PAO\Exception\NotFoundHttpException;


class View
{
    /**
     * 容器
     * @var Container
     */
    protected $container;

    /**
     * 模板公共变量
     * @var array
     */
    public $variables = [];

    /**
     * 模板路径
     * @var
     */
    protected $templates;


    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->templates = $this->container->config('template.dir')?:PAO.DIRECTORY_SEPARATOR.APP.DIRECTORY_SEPARATOR.'View';
    }


    /**
     * [twig 模板引擎]
     *
     * @return \Twig_Environment
     * @author  11
     * @version v1
     *
     */
    public function twig()
    {
        $loader = new \Twig_Loader_Filesystem($this->templates);
        /*
         * 添加模板路径
        $loader->addPath($templateDir3);
        $loader->prependPath($templateDir4);
        */
        $twig  = new \Twig_Environment($loader, array(

            //用来保存编译后模板的绝对路径，缺省值为false，也就是关闭缓存。
            'cache' => $this->container->config('template.cache')?:false,

            //生成的模板会有一个__toString()方法，可以用来显示生成的Node（缺省为false）
            'debug' => $this->container->config('config.debug')?:false,

            //当用Twig开发时，是有必要在每次模板变更之后都重新编译的。如果不提供一个auto_reload参数，他会从debug选项中取值
            'auto_reload' => $this->container->config('config.debug')?:false,

            //模板的字符集，缺省为utf-8。
            'charset' => $this->container->config('config.charset'),


            //如果设置为false，Twig会忽略无效的变量（无效指的是不存在的变量或者属性/方法），并将其替换为null。如果这个选项设置为true，那么遇到这种情况的时候，Twig会抛出异常。
            'strict_variables' =>true,

            /**
             * 如果设置为true, 则会为所有模板缺省启用自动转义（缺省为true）。
             * 在Twig 1.8中，可以设置转义策略（html或者js，要关闭可以设置为false）。
             * 在Twig 1.9中的转移策略，可以设置为css，url，html_attr，甚至还可以设置为回调函数。
             * 该函数需要接受一个模板文件名为参数，且必须返回要使用的转义策略，回调命名应该避免同内置的转义策略冲突。
             */
            'autoescape' => true,

            /**
             * 用于指出选择使用什么优化方式（缺省为-1，代表使用所有优化；设置为0则禁止）。
             */
            'optimizations' => -1,
        ));
        /*
        $lexer = new \Twig_Lexer($twig, array(
            'tag_comment' => array('{#', '#}'),
            'tag_block' => array('{%', '%}'),
            'tag_variable' => array('{^', '^}'),
            'interpolation' => array('#{', '}'),
        ));
        $twig->setLexer($lexer);
        */
        /**
         * 注册扩展方法
         * @var Twig_Environment
         *
         * $twig = new Twig_Environment($loader,array('debug'=>true));
         * $twig->addExtension(new Twig_Extension_Debug());
         */
        /**
         * 注册全局变量
         */
        $twig->addGlobal('PAO', PAO);
        $twig->addGlobal('APP', APP);
        $twig->addGlobal('config', $this->container->config('config'));
        $twig->addGlobal('request', $this->container->make('request'));
        $twig->addGlobal('timezone', date_default_timezone_get());
        /**
         * 注册全局make方法
         * @example   [make('class').function]
         * @var [type]
         */
        $make = new \Twig_SimpleFunction('make', function($alias, $parameters = []){
            return $this->container->make($alias, $parameters);
        });
        $twig->addFunction($make);

        /**
         * 注册config方法
         * @var [type]
         */
        $config = new \Twig_SimpleFunction('config', array($this->container->make('config'), 'get'));
        $twig->addFunction($config);

        /**
         * 设置web路径
         * @var [type]
         */
        $asset = new \Twig_SimpleFunction('asset', function($assets){
            $web = $this->container->config('config.web')?:$this->container->make('request')->root().'/';
            return $web . trim($assets, '/');
        });
        $twig->addFunction($asset);

        /**
         * 注册路由调用方法
         * @example [route(alias, ['a','b'])]
         * @var [type]
         */
        $route = new \Twig_SimpleFunction('route', array($this->container->make('route'), 'get'));
        $twig->addFunction($route);

        /**
         * 路由及url构建方法
         * @example [url('@as'), url('/path/path2')]
         * @var [type]
         */
        $url = new \Twig_SimpleFunction('url', function($url = null){
            $request = $this->container->make('request');
            $baseUrl = $request->baseUrl().'/';
            if(strstr($url, '@')) {
                $route = $this->container->make('route')->get(ltrim($url,'@'));
                return $baseUrl.ltrim($route, '/');
            }else if(strstr($url, '/')){
                return $baseUrl.trim($url, '/');
            }else{
                return $request->url();
            }
        });
        $twig->addFunction('url', $url);

        /**
         * 注册语言包调用方法
         * @var [type]
         */
        $language = new \Twig_SimpleFunction('e', array($this->container->make('translator'), 'get'));
        $twig->addFunction($language);


        /**
         * [$dump 注册调试函数]
         * @var [type]
         */
        $dump = function($variable){
                exit("<pre>".var_dump($variable)."</pre>");
        };

        $twig->addFunction(new \Twig_SimpleFunction('dump', $dump));

        /**
         * [$debug 注册debug函数]
         * @var [type]
         */
        $debug = function($variable){
            '<pre>'.print_r($variable)."</pre>";
        };

        $twig->addFunction(new \Twig_SimpleFunction('debug', $dump));

        $twig->addFunction('microtime', new \Twig_Function_Function('microtime'));

        $twig->addFunction('memory_get_usage', new \Twig_Function_Function('memory_get_usage'));

        /**
         * 单位转换
         */
        $twig->addFunction('size', new \Twig_Function_Function(function($size){
            $units = array('b','kb','mb','gb','tb','pb');
            return round($size/pow(1024,($i=floor(log($size,1024)))),2).$units[$i];
        }));

        /**
         * 注册过滤器
         */
        $twig->addFilter(new \Twig_SimpleFilter('dump', $dump));

        /**
         * [$suffix 截取字符串]
         * @var [type]
         */
        $twig->addFilter('cutstr', new \Twig_Filter_Function(function($string, $length, $suffix = false){
            return $string = mb_strlen($string)>$length
            ? ($suffix ? mb_substr($string, 0, $length).$suffix : mb_substr($string, 0, $length))
            : $string;
        }));


        return $twig;
    }


    /**
     * [assign 模板变量赋值方法]
     *
     * @param      $var [变量名]
     * @param null $val [变量值]
     * @author 11.
     */
    public function assign($var, $val = null)
    {
        if(is_array($var))
        {
            foreach($var as $key => $v)
            {
                $this->variables[$key] = $v;
            }
        }else{
            $this->variables[$var] = $val;
        }
    }

    /**
     * [render 模板渲染]
     *
     * @param $template
     * @param $variable
     * @return string
     * @author 11.
     */
    public function render($template, $variables)
    {
        $template = $template . $this->container->config('template.suffix');

        $variables = array_merge($this->variables, $variables);
        try {
            return $this->twig()->render($template, $variables);
        }catch (NotFoundHttpException $e){
            throw new NotFoundHttpException($e->getMessage());
        }

    }

    /**
     * [show 模板展示]
     *
     * @param $template
     * @param $variable
     * @author 11.
     */
    public function show($template, $variables)
    {
        return $this->container->make('response')->make($this->render($template, $variables));
    }
}
