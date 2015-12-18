<?php

namespace PAO\Http;


use Illuminate\Container\Container;
use PAO\Exception\SystemException;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MongoDbSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;

use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;


class Session implements SessionInterface, \IteratorAggregate, \Countable
{

    /**
     * SESSION标签名
     *
     * @var
     */
    protected $tag;

    /**
     * flash
     *
     * @var string
     */
    protected $flash;

    /**
     * 当前存储引擎
     *
     * @var
     */
    protected $storage;



    public function __construct()
    {
     //   parent::__construct(new PhpBridgeSessionStorage(), new  AttributeBag('pao_'));

        $handler = (string) Container::getInstance()->config('config.session') ?: 'files';
        $session = (array) Container::getInstance()->config('session');

        if(!in_array($handler, array_keys($session['storage']))) {
            throw new SystemException('The session handler ['.$handler.'] was not found! you can use handles by "'. implode('","', array_keys($session)).'"');
        }

        switch($handler)
        {

            case 'files':
                $this->storage = new NativeSessionStorage($session['options'], new NativeFileSessionHandler($session['storage'][$handler]['save_path']));
                break;

            case 'pdo':
                $dsn = "mysql:host={$session['storage'][$handler]['db_host']};port={$session['storage'][$handler]['db_port']};dbname={$session['storage'][$handler]['db_name']}";
                $this->storage = new NativeSessionStorage($session['options'], new PdoSessionHandler($dsn, $session['storage'][$handler]));

                break;

            case 'memcache':
                $this->storage = new NativeSessionStorage($session['options'], new MemcacheSessionHandler($session['storage'][$handler]));
                break;

            case 'memcached':
                $this->storage = new NativeSessionStorage($session['options'], new MemcachedSessionHandler($session['storage'][$handler]));
                break;

            case 'mongodb':
                $this->storage = new NativeSessionStorage($session['options'], new MongoDbSessionHandler($session['storage'][$handler], $session['storage'][$handler]));
                break;

            default:
                $this->storage = new NativeSessionStorage();
        }

        $attributes = new AttributeBag('pao_session_');
        $this->tag = $attributes->getName();
        $this->registerBag($attributes);

        $flashes = new FlashBag('pao_flash_');
        $this->flash = $flashes->getName();
        $this->registerBag($flashes);

    }

    /**
     * {@inheritdoc}
     */
    public function start()
    {
        return $this->storage->start();
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return $this->storage->getBag($this->tag)->has($name);
    }

    /**
     * {@inheritdoc}
     */
    public function get($name, $default = null)
    {
        return $this->storage->getBag($this->tag)->get($name, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        $this->storage->getBag($this->tag)->set($name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        return $this->storage->getBag($this->tag)->all();
    }

    /**
     * {@inheritdoc}
     */
    public function replace(array $attributes)
    {
        $this->storage->getBag($this->tag)->replace($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        return $this->storage->getBag($this->tag)->remove($name);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->storage->getBag($this->tag)->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function isStarted()
    {
        return $this->storage->isStarted();
    }

    /**
     * Returns an iterator for attributes.
     *
     * @return \ArrayIterator An \ArrayIterator instance
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->storage->getBag($this->tag)->all());
    }

    /**
     * Returns the number of attributes.
     *
     * @return int The number of attributes
     */
    public function count()
    {
        return count($this->storage->getBag($this->tag)->all());
    }

    /**
     * {@inheritdoc}
     */
    public function invalidate($lifetime = null)
    {
        $this->storage->clear();

        return $this->migrate(true, $lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function migrate($destroy = false, $lifetime = null)
    {
        return $this->storage->regenerate($destroy, $lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->storage->save();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->storage->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function setId($id)
    {
        $this->storage->setId($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->storage->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->storage->setName($name);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataBag()
    {
        return $this->storage->getMetadataBag();
    }

    /**
     * {@inheritdoc}
     */
    public function registerBag(SessionBagInterface $bag)
    {
        $this->storage->registerBag($bag);
    }

    /**
     * {@inheritdoc}
     */
    public function getBag($name)
    {
        return $this->storage->getBag($name);
    }

    /**
     * Gets the flashbag interface.
     *
     * @return FlashBagInterface
     */
    public function getFlashBag()
    {
        return $this->getBag($this->flash);
    }

}