<?php

namespace HollyIT\LaravelStatic;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;

class StaticRepository
{
    /**
     * @var Collection | AssetLibrary[]
     */
    protected $libraries;

    public function __construct()
    {
        $this->libraries = collect([]);
        $this->discover();
    }

    protected function discover()
    {
        Event::dispatch('laravel-static:register-libraries', $this);
    }

    public function add(AssetLibrary $library): static
    {
        $this->libraries->put($library->getName(), $library);

        return $this;
    }

    public function has($name): bool
    {
        return $this->libraries->has($name);
    }

    /**
     * @param $name
     * @return AssetLibrary|null
     */
    public function get($name): ?AssetLibrary
    {
        return $this->libraries->get($name);
    }

    public function findFromPath($pathName): ?AssetLibrary
    {
        return $this->libraries->first(fn (AssetLibrary $library) => Str::kebab($library->getName()) === $pathName);
    }

    /**
     * @return Collection | AssetLibrary[]
     * @noinspection PhpMissingReturnTypeInspection
     */
    public function all()
    {
        return $this->libraries;
    }

    public function require($name): RequiredLibraries
    {
        $instance = new RequiredLibraries($this);
        $instance->require($name);

        return $instance;
    }

    /**
     * @param  AssetLibrary  $library
     * @return Collection
     */
    public function getRequiredWithsFor(AssetLibrary $library): Collection
    {
        return $this->libraries->filter(fn (AssetLibrary $otherLibrary) => in_array(
            $library->getName(),
            $otherLibrary->getRequiredWith()
        ));
    }
}
