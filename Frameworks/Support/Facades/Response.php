<?php

use Illuminate\Support\Facades\Facade;

/**
 * Class Response
 *
 * @method static Response make(string $content = '', int $status = 200 , array $headers = [])
 * @method static Response show(string $content = '', int $status = 200 , array $headers = [])
 * @method static Response view(string $view, array $data = [], int $status = 200, array $headers = [])
 * @method static Response json(array $data = [], int $status = 200, array $headers = [], $options = 0)
 * @method static Response jsonp(Closure $callback, array $data = [], int $status = 200, array $headers = [], $options = 0)
 * @method static Response url(string $url, int $status = 302, array $headers = [])
 * @method static Response redirect(string $url, int $status = 302, array $headers = [])
 * @method static Response stream(Closure $callback, int $status = 200, array $headers = [])
 * @method static Response download(string $file, string $name = null, array $headers = [], string $disposition = 'attachment')
 */
class Response extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'response';
    }
}
