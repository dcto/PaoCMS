<?php

namespace PAO;


use Illuminate\Container\Container;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;


class Logger extends \Monolog\Logger  {


    protected $logger;

    protected $formatter;

    protected $container;

    public function __construct()
    {
        $this->name = APP;

        $this->handlers = [];

        $this->processors = [];

        $this->container = Container::getInstance();

        $this->logger = $this->container->config('config.dir.log').DIRECTORY_SEPARATOR;

        $logger = $this->logger.APP.DIRECTORY_SEPARATOR.'P_'.date('Ymd').'.log';

        $this->formatter = "[%datetime%] [%channel%] [%level_name%]: %message% %context% %extra%".PHP_EOL.PHP_EOL;

        $this->Stream($logger);
    }

    public function Stream($logger)
    {
        $this->handlers = array();
        $stream = new StreamHandler($logger, \Monolog\Logger::DEBUG);
        $stream->setFormatter(new LineFormatter($this->formatter));

        if($this->container->config('config.debug'))
        {
            $this->pushHandler($stream);
        }else{
            //用BufferHandler设置同一请求下日志数达到10条再写一次文件
            $this->pushHandler(new \Monolog\Handler\BufferHandler($stream, 100, \Monolog\Logger::DEBUG, true, true));
        }

    }


    public function to($logger, $app = null)
    {
        $this->name = $app ?: $this->name;

        $logger = $this->logger.trim($logger, '.log').'.log';
        $this->formatter = "[%datetime%] [%channel%] [%level_name%]: %message%".PHP_EOL.PHP_EOL;
        $this->Stream($logger);
        return $this;
    }

}