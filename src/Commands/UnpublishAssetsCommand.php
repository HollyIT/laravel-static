<?php

namespace HollyIT\LaravelStatic\Commands;

use Illuminate\Console\Command;
use HollyIT\LaravelStatic\StaticRepository;
use HollyIT\LaravelStatic\Contracts\DriverPublishesFiles;

class UnpublishAssetsCommand extends Command
{
    protected $signature = 'static-assets:unpublish {library?} {--all}';

    protected $description = 'Unpublish assets from a single library, or all if supplied with --all';

    public function handle(): int
    {
        $libraryName = $this->argument('library');
        $all = $this->option('all');
        $driver = app('laravel-static.file-driver');
        if (! ($driver instanceof DriverPublishesFiles)) {
            $this->error('The currently selected driver does not support publishing');

            return 0;
        }
        /** @var StaticRepository $repository */
        $repository = app(StaticRepository::class);

        if ($libraryName) {
            $library = $repository->get($libraryName);
            if (! $library) {
                $library = $repository->findFromPath($libraryName);
            }

            if (! $library) {
                $this->error('Unknown asset library ' . $libraryName);

                return 0;
            }

            $driver->unpublish($library);
            $this->output->success('Unpublished asset library ' . $libraryName);

            return 1;
        }

        if ($all) {
            foreach ($repository->all() as $libraryName) {
                $driver->unpublish($libraryName);
                $this->info('Unpublished library '.$libraryName->getName());
            }
            $this->output->success('Unpublished all libraries');

            return 1;
        }

        $this->error('You must either supply a library name or the --all flag');

        return 0;
    }
}
