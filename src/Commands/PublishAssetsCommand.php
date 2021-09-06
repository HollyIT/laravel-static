<?php

namespace HollyIT\LaravelStatic\Commands;

use Illuminate\Console\Command;
use HollyIT\LaravelStatic\StaticRepository;
use HollyIT\LaravelStatic\Contracts\DriverPublishesFiles;

class PublishAssetsCommand extends Command
{
    protected $signature = 'static-assets:publish';

    protected $description = 'Published all asset library files to the public location.';

    public function handle(): int
    {
        $driver = app('laravel-static.file-driver');
        if (! ($driver instanceof DriverPublishesFiles)) {
            $this->error('The currently selected driver does not support publishing');

            return 0;
        }
        /** @var StaticRepository $repository */
        $repository = app(StaticRepository::class);
        foreach ($repository->all() as $library) {
            $driver->publish($library);
            $this->info('Published library '.$library->getName());
        }

        return 1;
    }
}
