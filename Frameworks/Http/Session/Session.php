<?php

namespace PAO\Http\Session;


class Session implements \IteratorAggregate, \Countable
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name = 'PAO';

    /**
     * The session handler implementation.
     *
     * @var \SessionHandlerInterface
     */
    private $handler;

    /**
     * The session value encrypt
     * @var bool
     */
    private $encrypt = false;

    /**
     * Session store started status.
     *
     * @var bool
     */
    private $started = false;

    /**
     * Create a new session instance.
     *
     * @param  string $name
     * @param  \SessionHandlerInterface $handler
     * @param  string|null $id
     * @return void
     */
    public function __construct($name = 'pao', $handler = null, array $options = array())
    {
        session_cache_limiter(''); // disable by default because it's managed by HeaderBag (if used)
        ini_set('session.use_cookies', 1);
        session_register_shutdown();


        $this->encrypt = config('session.encrypt', false);

        $this->buildSessionHandler($handler?:config('app.session', 'files'), array_merge($options, config('session.options', array())));

        $this->start();
    }

    /**
     * [buildSessionHandler]
     *
     * @param null $handler
     * @param array $options
     * @return mixed
     */
    private function buildSessionHandler($handler = null, $options = array())
    {

        if($this->handler instanceof \SessionHandlerInterface){
           return $this->handler;
        }

        if(!in_array($handler, $handlers = array('files', 'redis', 'memcache', 'memcached', 'sqlite'))){
            throw new \InvalidArgumentException('Invalid '.$handler.' session handler, only supports by '. implode(',', $handlers));
        }
        app()->alias(__NAMESPACE__.'\\Handler\\'.ucfirst($handler).'SessionHandler','session.'.$handler);

        $this->setOptions($options);

        $this->handler = app('session.'. $handler);

        session_set_save_handler($this->handler, false);

        return $this->handler;
    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        if ($this->started) {
            return true;
        }

        if (\PHP_SESSION_ACTIVE === session_status()) {
            throw new \RuntimeException('Failed to start the session: already started by PHP.');
        }

        if (ini_get('session.use_cookies') && headers_sent($file, $line)) {
            throw new \RuntimeException(sprintf('Failed to start the session because headers have already been sent by "%s" at line %d.', $file, $line));
        }

        // ok to try and start the session
        if (!session_start()) {
            throw new \RuntimeException('Failed to start the session');
        }

         return $this->started = true;
    }


    /**
     * get handler
     *
     * @return \SessionHandlerInterface
     */
    public function handler()
    {
        if (\PHP_SESSION_ACTIVE != session_status() || !$this->started) {
            $this->start();
        }
        return $this->handler;
    }

    /**
     * Get or regenerate current session ID.
     *
     * @param bool $new
     *
     * @return string
     */
    public function id($new = false)
    {
        if ($new && session_id()) {
            session_regenerate_id(true);
        }
        return session_id() ?: null;
    }


    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        $keys = is_array($name) ? $name : func_get_args();

        return \Arr::exists($_SESSION, $keys);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        $value = \Arr::get($_SESSION, $name, $default);

        return $value ? ($this->encrypt ? make('crypt')->de($value) : $value) : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $value = $this->encrypt ? make('crypt')->en($value) : $value;

        return \Arr::set($_SESSION, $name, $value);
    }

    /**
     * Put a key / value pair or array of key / value pairs in the session.
     *
     * @param  string|array  $key
     * @param  mixed       $value
     * @return void
     */
    public function put($key, $value = null)
    {
        if (! is_array($key)) {
            $key = [$key => $value];
        }

        foreach ($key as $arrayKey => $arrayValue) {
            $this->set($arrayKey, $arrayValue);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * remove alias
     * @param $name
     * @return mixed
     */
    public function del($name)
    {
        $name = is_array($name) ? $name : func_get_args();
        return \Arr::forget($_SESSION, $name);
    }

    /**
     * Returns the number of attributes.
     *
     * @return int The number of attributes
     */
    public function count()
    {
        return count($_SESSION);
    }

    /**
     * Get the CSRF token value.
     *
     * @return string
     */
    public function token()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return $this->destroy();
    }

    /**
     * flush session
     */
    public function flush()
    {
        $_SESSION = array();
    }

    /**
     * Destroy the session.
     */
    public function destroy()
    {
        $this->started = false;
        if ($this->id()) {
            session_unset();
            session_destroy();
            session_write_close();
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 4200,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
        }
    }

    /**
     * 重新生成session_id
     * @param bool $delete 是否删除关联会话文件
     * @return bool
     */
    public function regenerate($delete = false)
    {
       return session_regenerate_id($delete);
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return $this->started;
    }

    /**
     * Sets session.* ini variables.
     *
     * For convenience we omit 'session.' from the beginning of the keys.
     * Explicitly ignores other ini keys.
     *
     * @param array $options Session ini directives array(key => value)
     *
     * @see http://php.net/session.configuration
     */
    public function setOptions(array $options)
    {
        $options = \Arr::only($options,array(
            'name',
            'referer_check',
            'serialize_handler',
            'use_cookies',
            'use_only_cookies',
            'use_trans_sid',
            'cache_limiter',
            'cookie_domain',
            'cookie_httponly',
            'cookie_lifetime',
            'cookie_path',
            'cookie_secure',
            'entropy_file',
            'entropy_length',
            'gc_divisor',
            'gc_maxlifetime',
            'gc_probability',
            'hash_bits_per_character',
            'hash_function',
            'upload_progress.enabled',
            'upload_progress.cleanup',
            'upload_progress.prefix',
            'upload_progress.name',
            'upload_progress.freq',
            'upload_progress.min-freq',
            'url_rewriter.tags',
        ));
        foreach ($options as $key => $value) {
                ini_set('session.'.$key, $value);
        }
    }

    /**
     * Returns an iterator for attributes.
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator()
    {
        return new \ArrayIterator($_SESSION);
    }
}