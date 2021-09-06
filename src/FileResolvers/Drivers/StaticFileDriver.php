<?php

namespace HollyIT\LaravelStatic\FileResolvers\Drivers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use HollyIT\LaravelStatic\AssetLibrary;

abstract class StaticFileDriver
{
    /**
     * @var array
     */
    protected array $config;

    protected array $manifests = [];

    protected bool $doShutdown = false;

    public static string $cacheKey = 'laravel-static-manifests';

    protected int $cacheTtl;

    public function __construct($config = [])
    {
        $this->config = $config;
        $this->cacheTtl = config('laravel-static.manifest_cache_ttl', 86400);
        if ($this->cacheTtl) {
            $this->manifests = Cache::get(static::$cacheKey, []);
        }
    }

    public function getConfig(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->config, $key, $default);
    }

    public function setConfig(string $key, mixed $value): static
    {
        Arr::set($this->config, $key, $value);

        return $this;
    }

    public function getManifest(AssetLibrary $library): array
    {
        $path = $library->getManifestPath();
        if (! array_key_exists($path, $this->manifests)) {
            if ($path && file_exists($path)) {
                $this->manifests[$path] = json_decode(file_get_contents($path), true);
            } else {
                $this->manifests[$path] = [];
            }
            if ($this->cacheTtl) {
                Cache::put(static::$cacheKey, $this->manifests, $this->cacheTtl);
            }
        }

        return $this->manifests[$path];
    }

    protected function pathIsExternal(string $path): bool
    {
        return Str::contains($path, '://');
    }

    protected function getManifestPath(string $path, AssetLibrary $library)
    {
        $manifest = $this->getManifest($library);
        $search = Str::startsWith($path, '/') ? $path : '/'.$path;

        return $manifest[$search] ?? $path;
    }

    abstract public function resolve(AssetLibrary $library, string $file): string;
}
