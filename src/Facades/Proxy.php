<?php

namespace Jundayw\ProxyManager\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use Jundayw\Proxy\Contracts\Proxied;
use Jundayw\Proxy\Contracts\ProxyManager as ProxyManagerContract;
use Jundayw\Proxy\ProxyManager;

/**
 * @method static string|Proxied create(object|string $className, Closure|null $classFactoryCallback = null)
 *
 * @see ProxyManager
 */
class Proxy extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ProxyManagerContract::class;
    }
}
