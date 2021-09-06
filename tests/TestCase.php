<?php

namespace HollyIT\LaravelStatic\Tests;

use Illuminate\Support\Facades\Event;
use HollyIT\LaravelStatic\AssetLibrary;
use HollyIT\LaravelStatic\StaticRepository;
use HollyIT\LaravelStatic\RequiredLibraries;
use Orchestra\Testbench\TestCase as Orchestra;
use HollyIT\LaravelStatic\LaravelStaticServiceProvider;
use HollyIT\LaravelStatic\Contracts\DriverDefinesRoutes;

class TestCase extends Orchestra
{
    protected StaticRepository $repository;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelStaticServiceProvider::class,
        ];
    }

    public function setFileDriver($name)
    {
        $this->app->singleton('laravel-static.file-driver', function ($app) use ($name) {
            $driverName = $name;
            $driver = $driverName ? config('laravel-static.drivers.'.$driverName) : null;
            if (! $driver) {
                throw new \Exception('Unable to initialize static file driver');
            }
            $class = $driver['class'] ?? null;
            if (! $class || ! class_exists($class)) {
                throw new \Exception('Unable to initialize static file driver class');
            }
            unset($driver['class']);

            $instance = $app->make($class, [
                'config' => $driver,
            ]);
            if ($instance instanceof DriverDefinesRoutes) {
                $instance->routes();
            }

            return $instance;
        });
    }

    public function setupDefaultLibraries()
    {
        Event::listen('laravel-static:register-libraries', function ($repository) {
            $repository->add(
                AssetLibrary::create('core')
                    ->publicPath(__DIR__.'/support/libraries/core/public')
                    ->withJs('core.js')
                    ->withCss('core.css')
            );

            $repository->add(
                AssetLibrary::create('admin-theme')
                    ->publicPath(__DIR__.'/support/libraries/admin/public')
                    ->withJs('admin.js')
                    ->withCss('admin.css')
                    ->dependsOn('core')
            );
        });
        $this->repository = app(StaticRepository::class);
    }

    public function assertRequiresOrder($expected, RequiredLibraries $requires)
    {
        $this->assertEquals(
            $expected,
            $requires->required()->map(fn ($library) => $library->getName())->values()->toArray()
        );
    }
}
