<?php

use HollyIT\LaravelStatic\FileResolvers\Drivers\DevDriver;
use HollyIT\LaravelStatic\FileResolvers\Drivers\FileDriver;
use HollyIT\LaravelStatic\FileResolvers\Drivers\LazyDriver;

return [
    // The file resolver driver we will be using.
    'driver'  => env('STATIC_FILE_DRIVER', 'file'),

    // The number of hours to cache manifests. Setting this to 0
    // will disable caching.
    'manifest_cache_ttl' => 86400,

    'drivers' => [
        // The most common file resolver. It will serve from published files
        // in the public_path.
        'file' => [
            'class'      => FileDriver::class,
            // The base URL where static files are located.
            'url'        => env('APP_URL').'/static',
            // The base path where static files are published to.
            'publish_to' => public_path('static')
        ],

        // Lazy solves the problem of requiring files to be published.
        // Instead, a special controller will serve files directly from
        // your extension. This is fine for smaller sites, but on
        // high traffic sites can have performance issues.

        'lazy' => [
            'class'        => LazyDriver::class,
            // The prefix of the route that is defined to serve lazy files.
            'route_prefix' => '_laravel_static'
        ],

        // For development only. If a hot file is present, then the URL's
        // will be rewritten to the webpack dev server. If not then
        // files will be rewritten to the lazy resolver.
        // SHOULD NEVER BE USED IN PRODUCTION.
        'dev'  => [
            'class' => DevDriver::class,
        ],
    ]
];
