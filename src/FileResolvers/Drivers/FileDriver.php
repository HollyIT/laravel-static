<?php

namespace HollyIT\LaravelStatic\FileResolvers\Drivers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use HollyIT\LaravelStatic\AssetLibrary;
use HollyIT\LaravelStatic\Contracts\DriverPublishesFiles;

class FileDriver extends StaticFileDriver implements DriverPublishesFiles
{
    public function resolve(AssetLibrary $library, string $file): string
    {
        $file = $this->getManifestPath($file, $library);

        return rtrim($this->getConfig('url', '/static'), '/').'/'.Str::kebab($library->getName()).'/'.trim($file, '/');
    }

    public function beforePublish()
    {
        if (File::exists($this->getConfig('publish_to'))) {
            File::deleteDirectory($this->getConfig('publish_to'));
        }
    }

    public function unpublish(AssetLibrary $library)
    {
        $destination = $this->getConfig('publish_to').'/'.Str::kebab($library->getName());
        if (File::exists($destination)) {
            File::deleteDirectory($destination);
        }
    }

    public function publish(AssetLibrary $library)
    {
        $this->unpublish($library);
        $destination = $this->getConfig('publish_to').'/'.Str::kebab($library->getName());

        if (! File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }
        File::copyDirectory($library->getPublicPath(), $destination);
    }
}
