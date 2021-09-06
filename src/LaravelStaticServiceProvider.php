<?php

namespace HollyIT\LaravelStatic;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\CachesRoutes;
use HollyIT\LaravelStatic\FileResolvers\FileResolver;
use HollyIT\LaravelStatic\Commands\PublishAssetsCommand;
use HollyIT\LaravelStatic\Contracts\DriverDefinesRoutes;
use HollyIT\LaravelStatic\Commands\UnpublishAssetsCommand;

class LaravelStaticServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laravel-static.php', 'laravel-static');
        $this->app->singleton(FileResolver::class);
        $this->app->singleton('laravel-static.file-driver', function ($app) {
            $driverName = config('laravel-static.driver', 'file');
            $driver = $driverName ? config('laravel-static.drivers.'.$driverName) : null;
            if (! $driver) {
                throw new \Exception('Unable to initialize static file driver');
            }
            $class = $driver['class'] ?? null;
            if (! $class || ! class_exists($class)) {
                throw new \Exception('Unable to initialize static file driver class');
            }
            unset($driver['class']);

            return $app->make($class, [
                'config' => $driver,
            ]);
        });
    }

    public function boot()
    {
        if (! ($this->app instanceof CachesRoutes && $this->app->routesAreCached())) {
            $driver = $this->app['laravel-static.file-driver'];
            if ($driver instanceof DriverDefinesRoutes) {
                $driver->routes();
            }
        }

        if ($this->app->runningInConsole() || $this->app->runningUnitTests()) {
            $this->commands([
                PublishAssetsCommand::class,
                UnpublishAssetsCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/laravel-static.php' => config_path('laravel-static.php'),
            ], 'config');
        }
    }
}
