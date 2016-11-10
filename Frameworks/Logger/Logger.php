<?php

namespace PAO\Logger;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger as MonologLogger;

class Logger implements Log{


    /**
     * @var \Monolog\Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $logfile;

    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug'     => MonologLogger::DEBUG,
        'info'      => MonologLogger::INFO,
        'notice'    => MonologLogger::NOTICE,
        'warning'   => MonologLogger::WARNING,
        'error'     => MonologLogger::ERROR,
        'critical'  => MonologLogger::CRITICAL,
        'alert'     => MonologLogger::ALERT,
        'emergencyc' => MonologLogger::EMERGENCY,
    ];



    public function __construct()
    {
        $this->logger = new MonologLogger('PAO');
        $this->logfile = rtrim(PAO,'/').'/'.trim(config('app.dir.log'),'/').'/'.trim(ucfirst(APP),'/').'/'.date('Ymd').'.log';
        $this->logger->pushHandler(new StreamHandler($this->logfile));
    }


    /**
     * Log an alert message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->pushToLogger(__FUNCTION__, $message, $context);
    }

    /**
     * Log a critical message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->pushToLogger(__FUNCTION__, $message, $context);
    }

    /**
     * Log an error message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->pushToLogger(__FUNCTION__, $message, $context);
    }

    /**
     * Log a warning message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->pushToLogger(__FUNCTION__, $message, $context);
    }

    /**
     * Log a notice to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->pushToLogger(__FUNCTION__, $message, $context);
    }


    /**
     * Log an informational message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->pushToLogger(__FUNCTION__, $message, $context);
    }

    /**
     * Log a debug message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->pushToLogger(__FUNCTION__, $message, $context);
    }


    /**
     * Log a message to the logs.
     *
     * @param  string  $level
     * @param  string  $message
     * @param  array  $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        $this->pushToLogger($level, $message, $context);
    }

    public function file($path, $level = 'debug')
    {
        $path = rtrim(config('app.dir.log'),'/').'/'.rtrim(ltrim($path,'/'),'.log').'.log';
        $this->useFiles($path, $level);
        return $this;
    }

    /**
     * Register a file log handler.
     *
     * @param  string  $path
     * @param  string  $level
     * @return void
     */
    public function useFiles($path, $level = 'debug')
    {
        $this->logger->pushHandler($handler = new StreamHandler($path, $this->parseLevel($level)));
        $handler->setFormatter($this->getDefaultFormatter());
    }
    /**
     * Register a daily file log handler.
     *
     * @param  string  $path
     * @param  int     $days
     * @param  string  $level
     * @return void
     */
    public function useDailyFiles($path, $days = 0, $level = 'debug')
    {
        $this->logfile = $this->container->config('app.dir.log').'/'.rtrim($path,'/').date('Y/m/d');
        $this->logger->pushHandler(new StreamHandler($this->logfile));
    }

    /**
     * Register a Syslog handler.
     *
     * @param  string  $name
     * @param  string  $level
     * @return \Psr\Log\LoggerInterface
     */
    public function useSyslog($name = 'laravel', $level = 'debug')
    {
        return $this->logger->pushHandler(new SyslogHandler($name, LOG_USER, $level));
    }

    /**
     * Register an error_log handler.
     *
     * @param  string  $level
     * @param  int  $messageType
     * @return void
     */
    public function useErrorLog($level = 'debug', $messageType = ErrorLogHandler::OPERATING_SYSTEM)
    {
        $this->logger->pushHandler(
            $handler = new ErrorLogHandler($messageType, $this->parseLevel($level))
        );
        $handler->setFormatter($this->getDefaultFormatter());
    }

    /**
     * @param null $message
     */
    private function formatMessage($message = null)
    {
        if (is_array($message)) {
            return var_export($message, true);
        } elseif ($message instanceof Jsonable) {
            return $message->toJson();
        } elseif ($message instanceof Arrayable) {
            return var_export($message->toArray(), true);
        }
        return $message;
    }

    /**
     * Get the underlying Monolog instance.
     *
     * @return \Monolog\Logger
     */
    public function Logger()
    {
        return $this->logger;
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param  string  $level
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    protected function parseLevel($level)
    {
        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }
        throw new \InvalidArgumentException('Invalid log level.');
    }

    /**
     * Get a default Monolog formatter instance.
     *
     * @return \Monolog\Formatter\LineFormatter
     */
    protected function getDefaultFormatter()
    {
        return new LineFormatter(null, null, true, true);
    }

    /**
     * @param $level
     * @param $message
     * @param array $context
     */
    private function pushToLogger($level, $message, array $context = [])
    {
        $this->logger->{$level}($this->formatMessage($message), $context);
    }
}