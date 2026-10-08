<?php

namespace Jundayw\ProxyManager;

use Illuminate\Support\ServiceProvider;
use Jundayw\Proxy\Contracts\Configuration as ConfigurationContract;
use Jundayw\Proxy\Contracts\ProxyGenerator as GeneratorContract;
use Jundayw\Proxy\Contracts\ProxyManager as ManagerContract;
use Jundayw\Proxy\Configuration;
use Jundayw\Proxy\ProxyGenerator;
use Jundayw\Proxy\ProxyManager;
use Jundayw\ProxyManager\Facades\Proxy;

class ProxyManagerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../../proxy/config/proxy.php', 'proxy');
        }

        $this->app->instance(ConfigurationContract::class, $configuration = new Configuration(config('proxy', [])));
        $this->app->bind(GeneratorContract::class, ProxyGenerator::class);
        $this->app->bind(ManagerContract::class, ProxyManager::class);

        foreach ($configuration->getProxyInterfaces() as $interface) {
            $this->app->beforeResolving($interface, function (string $abstract) use ($interface) {
                if ($this->app->bound($abstract) || $interface->isProxied($abstract)) {
                    return;
                }

                $concrete = Proxy::create($abstract);

                if ($abstract === $concrete) {
                    return;
                }

                $this->app->bind($abstract, $concrete);
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
            $this->registerCommands();
        }
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../../proxy/config/proxy.php' => config_path('proxy.php'),
        ], 'config');
    }

    /**
     * Register the package's console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            Console\CompileCommand::class,
            Console\ClearCompiledCommand::class,
        ]);
    }

}
