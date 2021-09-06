<?php

namespace HollyIT\LaravelStatic\FileResolvers;

use Illuminate\Foundation\Application;
use HollyIT\LaravelStatic\AssetLibrary;
use HollyIT\LaravelStatic\FileResolvers\Drivers\StaticFileDriver;

class FileResolver
{
    protected Application $app;
    protected StaticFileDriver $driver;

    public function __construct(Application $app, $driver = null)
    {
        $this->app = $app;
        $this->driver = $driver ?? $app['laravel-static.file-driver'];
    }

    /**
     * @return StaticFileDriver
     */
    public function getDriver(): mixed
    {
        return $this->driver;
    }

    public function resolve(AssetLibrary $library, string $file): string
    {
        return $this->driver->resolve($library, $file);
    }
}
