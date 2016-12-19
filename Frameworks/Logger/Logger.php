<?php

namespace PAO\Logger;

use Psr\Log\LogLevel;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Katzgrau\KLogger\Logger as KLogger;

class Logger extends KLogger{

    /**
     * Logger constructor.
     * @param string $logDirectory
     * @param string $logLevelThreshold
     * @param array $options
     */
    public function __construct($logDirectory = null, $logLevelThreshold = LogLevel::DEBUG, array $options = array())
    {
        parent::__construct(
            $logDirectory = $logDirectory?:path(config('dir.logs'),APP),
            LogLevel::DEBUG,
            array(
                'prefix'=>'pao_',
                'dateFormat'=>'Y-m-d H:i:s.u',
            )
        );
    }

    /**
     * Log a message to file
     *
     * @param $path
     * @param string $level
     * @return $this
     */
    public function dir()
    {
        $path = implode(DIRECTORY_SEPARATOR, func_get_args());
        $dir = pathinfo($path, PATHINFO_DIRNAME);
        if(pathinfo($path, PATHINFO_EXTENSION)){
            $this->options['filename'] = basename($path);
        }
        if(!is_dir($dir = path(config('dir.logs'), $dir))){
            mkdir($dir, 0777, true);
        }
        $this->setLogFilePath($dir);
        $this->setFileHandle('a');
        return $this;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    protected function formatMessage($level, $message, $context)
    {
        if (is_array($message)) {
            $message = var_export($message, true);
        } elseif ($message instanceof Jsonable) {
            $message = $message->toJson();
        } elseif ($message instanceof Arrayable) {
            $message = var_export($message->toArray(), true);
        }
        return parent::formatMessage($level, $message, $context);
    }

}