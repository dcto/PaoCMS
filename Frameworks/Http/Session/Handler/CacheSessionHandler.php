<?php

namespace PAO\Http\Session\Handler;

use PAO\Cache\Driver\DriverInterface;
use SessionHandlerInterface;

class CacheSessionHandler implements SessionHandlerInterface
{
    /**
     * The path where sessions should be stored.
     *
     * @var DriverInterface
     */
    protected $cache;

    /**
     * The number of minutes the session should be valid.
     *
     * @var int
     */
    protected $time;

    /**
     * CacheSessionHandler constructor.
     *
     * @param DriverInterface $cache
     * @param int $time
     */
    public function __construct(DriverInterface $cache, $time = 1800)
    {
        $this->cache = $cache;
        $this->time = $time;
    }

    /**
     * {@inheritdoc}
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($session_id)
    {
        return $this->cache->get($session_id);
    }

    /**
     * {@inheritdoc}
     */
    public function write($session_id, $session_data)
    {
        return $this->cache->set($session_id, $session_data, $this->time);
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($session_id)
    {
        return $this->cache->del($session_id);
    }

    /**
     * {@inheritdoc}
     */
    public function gc($lifetime)
    {
        return true;
    }
}
