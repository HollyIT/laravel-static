<?php

namespace HollyIT\LaravelStatic\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use HollyIT\LaravelStatic\FileResolvers\Drivers\StaticFileDriver;

class ClearManifestCacheCommand extends Command
{
    protected $signature = 'static-assets:clear-cache';
    protected $description = 'Clears the manifest cache';

    public function handle(): int
    {
        Cache::forget(StaticFileDriver::$cacheKey);
        $this->output->success('Manifest cache has been cleared');

        return 1;
    }
}
