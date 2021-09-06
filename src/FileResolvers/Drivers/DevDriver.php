<?php

namespace HollyIT\LaravelStatic\FileResolvers\Drivers;

use HollyIT\LaravelStatic\AssetLibrary;

class DevDriver extends LazyDriver
{
    protected array $hotFiles = [];

    public function __construct($config = [])
    {
        parent::__construct($config);
        // For dev we don't want to cache our manifest.

        $this->manifests = [];
        $this->cacheTtl = 0;
    }

    protected function getHot(AssetLibrary $library): ?string
    {
        $path = $library->getHotFile();

        if (! array_key_exists($path, $this->hotFiles)) {
            $this->hotFiles[$path] = file_exists($path) ? trim(file_get_contents($path)) : null;
        }

        return $this->hotFiles[$path];
    }

    public function resolve(AssetLibrary $library, string $file): string
    {
        if ($hot = $this->getHot($library)) {
            return trim($hot, '/').'/'.trim($file, '/');
        }

        return parent::resolve($library, $file);
    }
}
