<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Session
 *
 * @method static Session start()
 * @method static Session all()
 * @method static Session has(string $name)
 * @method static Session get(string $name, mixed $default = null)
 * @method static Session set(string $name, mixed $value)
 * @method static Session del(string $name)
 * @method static Session save()
 * @method static Session getId()
 * @method static Session setId(string $id)
 * @method static Session clear()
 * @method static Session count()
 * @method static Session getBag()
 * @method static Session getName()
 * @method static Session setName(string $name)
 * @method static Session getFlashBag()
 * @method static Session isStarted()
 * @method static Session invalidate()
 * @method static Session migrate()
 * @method static Session getMetadataBag(string $name)
 * @method static Session registerBag(string $name)
 * @method static Session replace(array $attributes)
 * @method static Session remove(string $name)
 */
class Session extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'session';
    }
}
