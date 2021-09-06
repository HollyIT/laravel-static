<?php

namespace HollyIT\LaravelStatic\FileResolvers\Drivers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use HollyIT\LaravelStatic\AssetLibrary;
use HollyIT\LaravelStatic\Contracts\DriverDefinesRoutes;
use HollyIT\LaravelStatic\Http\Controllers\StaticController;

class LazyDriver extends StaticFileDriver implements DriverDefinesRoutes
{
    public static string $routeName = 'laravel_static_server';

    public function routes()
    {
        Route::get(
            $this->getConfig('route_prefix', '_laravel_static').'/{library}/{path}',
            [StaticController::class, 'handle']
        )
            ->name(static::$routeName)
            ->whereAlphaNumeric('library')
            ->where('path', '.*');
    }

    protected function toUrl(AssetLibrary $library, $file): string
    {
        return URL::route(
            static::$routeName,
            [
                'library' => Str::kebab($library->getName()),
                'path' => $file,
            ]
        );
    }

    public function resolve(AssetLibrary $library, string $file): string
    {
        $file = $this->getManifestPath($file, $library);

        return $this->toUrl($library, trim($file, '/'));
    }
}
