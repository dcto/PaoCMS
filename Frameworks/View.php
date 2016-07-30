<?php

namespace PAO;


use Illuminate\Support\Str;
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
        $this->templates = $this->container->config('template.dir')?:APP.DIRECTORY_SEPARATOR.'View';
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
            'strict_variables' =>false,

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
        $twig->addGlobal('APP', basename(APP));
        $twig->addGlobal('config', $this->container->config('config'));
        $twig->addGlobal('request', $this->container->make('request'));
        $twig->addGlobal('timezone', date_default_timezone_get());
        $twig->addGlobal('lang', $this->container->make('lang')->all());


        /**
         * 注册全局可用php函数
         * @example {{php_function()}}
         */
        $twig->addFunction(new \Twig_SimpleFunction('php_*',
            function() {
                $args = func_get_args();

                $function = array_shift($args);

                return call_user_func_array($function, $args);
            },
            array('pre_escape' => 'html', 'is_safe' => array('html'))
            )
        );

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
         * 设置访问路径
         * @var [type]
         */
        $asset = new \Twig_SimpleFunction('asset', function($path = null){
            $url = trim($this->container->make('request')->root(),'/').'/';
           if(Str::startsWith($path, '/')){
               return $url.str_replace('//','/', trim($path, '/'));
           }else{
               return $url.str_replace('//', '/', trim(strtolower(NAME).'/'.$path, '/'));
           }
        });
        $twig->addFunction($asset);

        /**
         * 注册路由调用方法
         * @example [route(alias, ['a','b'])]
         * @var [type]
         */
        $router = new \Twig_SimpleFunction('route', array($this->container->make('router'), 'router'));
        $twig->addFunction($router);

        /**
         * [获取当前 URL]
         * @param null $do [构建 URL 参数 @=获了路由, $=根据当前控制器,控制器方法获取 url,]
         * @example url('@index')
         * @example url('$controller');
         * @example url('/index/abc');
         * @example url();
         * @return string
         */
        $url = new \Twig_SimpleFunction('url',  array($this->container->make('request'), 'url'));
        $twig->addFunction('url', $url);

        /**
         * [uri 获取当前url包含所有参数]
         * @param null $cast [排除或抽取批定URL参数]
         */
        $uri = new \Twig_SimpleFunction('uri', function($cast = null){
            return $this->container->make('request')->uri($cast);

        });
        $twig->addFunction('uri', $uri);

        /**
         * 注册语言包调用方法
         * @var [type]
         */
        $twig->addFunction('lang', new \Twig_SimpleFunction('lang', array($this->container->make('lang'), 'get')));


        /**
         * [$dump 注册调试函数]
         * @var [type]
         */
        $dump = function($variable){
               echo "<pre>".var_dump($variable)."</pre>";
        };
        $twig->addFunction(new \Twig_SimpleFunction('dump', $dump,  array('pre_escape' => 'html', 'is_safe' => array('html'))));

        /**
         * [$debug 注册debug函数]
         * @var [type]
         */
        $debug = function($variable){
            echo "<pre>".print_r($variable)."</pre>";
        };

        $twig->addFunction(new \Twig_SimpleFunction('debug', $debug, array('pre_escape' => 'html', 'is_safe' => array('html'))));

        $twig->addFunction(new \Twig_SimpleFunction('microtime', function($parameters){
            return microtime($parameters);}
        ));

        $twig->addFunction(new \Twig_SimpleFunction('memory_get_usage', function($parameters){
            return memory_get_usage($parameters);
        }));

        /**
         * 单位转换
         */
        $twig->addFunction('size', new \Twig_SimpleFunction('size', function($size){
            $units = array('b','kb','mb','gb','tb','pb');
            return round($size/pow(1024,($i=floor(log($size,1024)))),2).$units[$i];
        }));

        /**
         * 注册过滤器
         */
        $twig->addFilter(new \Twig_SimpleFilter('dump', $dump));
        $twig->addFilter(new \Twig_SimpleFilter('debug', $debug));

        /**
         * [$suffix 截取字符串]
         * @var [type]
         */
        $twig->addFilter(new \Twig_SimpleFilter('len',function($string, $length, $suffix = false){
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
        $template = $template . $this->container->config('template.append');

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
