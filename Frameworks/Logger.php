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
        $this->name = basename(APP);

        $this->handlers = [];

        $this->processors = [];

        $this->container = Container::getInstance();

        $this->logger = $this->container->config('config.dir.log').DIRECTORY_SEPARATOR;

        $logger = $this->logger.basename(APP).DIRECTORY_SEPARATOR.$this->container->config('config.token').'_'.date('Ymd').'.log';

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
            //��BufferHandler����ͬһ��������־���ﵽ10����дһ���ļ�
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