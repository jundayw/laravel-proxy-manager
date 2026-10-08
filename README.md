<a id="readme-top"></a>

# Laravel Proxy Manager

**Proxy Manager integration for Laravel** — a lightweight, type-safe, inheritance-based extension
that brings dynamic proxies and aspect-oriented programming (AOP) to Laravel. Define aspects
once, and let proxies weave cross-cutting concerns like logging, caching, transactions, and
authorization into your services — without touching business logic.

[![GitHub Tag][GitHub Tag]][GitHub Tag URL]
[![Total Downloads][Total Downloads]][Packagist URL]
[![Packagist Version][Packagist Version]][Packagist URL]
[![Packagist PHP Version Support][Packagist PHP Version Support]][Repository URL]
[![Packagist License][Packagist License]][Repository URL]

<!-- TABLE OF CONTENTS -->
<details>
    <summary>Table of Contents</summary>
    <ol>
        <li><a href="#installation">Installation</a></li>
        <li><a href="#usage">Usage</a></li>
        <li><a href="#contributing">Contributing</a></li>
        <li><a href="#contributors">Contributors</a></li>
        <li><a href="#license">License</a></li>
    </ol>
</details>

<!-- INSTALLATION -->

## Installation

You can install the package via [Composer]:

```bash
composer require jundayw/laravel-proxy-manager
```

You can publish the config file with:

```shell
php artisan vendor:publish --provider="Jundayw\ProxyManager\ProxyManagerServiceProvider" --tag="config"
```

<p align="right">[<a href="#readme-top">back to top</a>]</p>

<!-- USAGE EXAMPLES -->

## Quick Start

Just extend `Jundayw\Proxy\Contracts\Proxy` and add the `Jundayw\Proxy\Attributes\Proxy` middleware attribute.

```php
<?php

use Jundayw\Proxy\Attributes\IgnoreProxy;
use Jundayw\Proxy\Attributes\Proxy;
use Jundayw\Proxy\Attributes\ProxyIgnore;
use Jundayw\Proxy\Contracts\Proxy as AOP;

#[Proxy([
    new TraceMiddleware(),
    new LoggingMiddleware(),
    new TimingMiddleware(),
])]
class OrderService implements AOP
{
    public function create(
        string $orderNo,
        int|float $amount,
    ): ?Order {
        // Business logic
    }

    public function find(
        int|string $id,
    ): ?Order {
        // Business logic
    }

    #[IgnoreProxy]
    public function healthCheck(): string
    {
        return 'ok';
    }

    #[ProxyIgnore]
    public function internalCalculation(
        int $amount,
    ): int {
        return $amount * 100;
    }
}
```

## Built-in Attributes

### `#[Proxy]`

`#[Proxy]` enables proxy generation and defines the middleware pipeline.

```php
#[Proxy([
    new TraceMiddleware(),
    new LoggingMiddleware(),
    new TimingMiddleware(),
])]
class OrderService
{
}
```

Middleware is executed according to the configured pipeline.

### `#[IgnoreProxy]`

Use `#[IgnoreProxy]` to exclude a method from proxy interception.

```php
#[IgnoreProxy]
public function healthCheck(): string
{
    return 'ok';
}
```

Typical use cases include:

* Health checks
* Internal methods
* Framework callbacks
* Lightweight methods
* Performance-sensitive methods

### `#[ProxyIgnore]`

`#[ProxyIgnore]` provides method-level proxy exclusion.

```php
#[ProxyIgnore]
public function internalCalculation(
    int $amount,
): int {
    return $amount * 100;
}
```

This makes proxy behavior explicit at the method level.

## Middleware

Middleware is the primary extension point of Proxy Manager.

For example:

```php
<?php

namespace App\Proxy\Middleware;

use Jundayw\Proxy\Contracts\Middleware;
use Jundayw\Proxy\Invocation;

class TimingMiddleware implements Middleware
{
    public function __invoke($request, Closure $next)
    {
        return $next($request);
    }
}
```

Middleware can be used for:

* Logging
* Tracing
* Metrics
* Authorization
* Transactions
* Caching
* Idempotency
* Auditing
* Performance monitoring

<!-- CONTRIBUTING -->

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

<p align="right">[<a href="#readme-top">back to top</a>]</p>

<!-- CONTRIBUTORS -->

## Contributors

Thanks goes to these wonderful people:

<a href="https://github.com/jundayw/laravel-proxy-manager/graphs/contributors">
  <img src="https://contrib.rocks/image?repo=jundayw/laravel-proxy-manager" alt="contrib.rocks image" />
</a>

Contributions of any kind are welcome!

<p align="right">[<a href="#readme-top">back to top</a>]</p>

<!-- LICENSE -->

## License

Distributed under the MIT License (MIT). Please see [License File] for more information.

<p align="right">[<a href="#readme-top">back to top</a>]</p>

[GitHub Tag]: https://img.shields.io/github/v/tag/jundayw/laravel-proxy-manager

[Total Downloads]: https://img.shields.io/packagist/dt/jundayw/laravel-proxy-manager?style=flat-square

[Packagist Version]: https://img.shields.io/packagist/v/jundayw/laravel-proxy-manager

[Packagist PHP Version Support]: https://img.shields.io/packagist/php-v/jundayw/laravel-proxy-manager

[Packagist License]: https://img.shields.io/github/license/jundayw/laravel-proxy-manager

[GitHub Tag URL]: https://github.com/jundayw/laravel-proxy-manager/tags

[Packagist URL]: https://packagist.org/packages/jundayw/laravel-proxy-manager

[Repository URL]: https://github.com/jundayw/laravel-proxy-manager

[GitHub Open Issues]: https://github.com/jundayw/laravel-proxy-manager/issues

[Composer]: https://getcomposer.org

[License File]: https://github.com/jundayw/laravel-proxy-manager/blob/main/LICENSE
