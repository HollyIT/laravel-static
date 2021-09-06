<?php

namespace HollyIT\LaravelStatic;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use HollyIT\LaravelStatic\FileResolvers\FileResolver;

class RequiredLibraries
{
    protected Collection $requires;
    protected StaticRepository $repository;

    public function __construct(StaticRepository $repository)
    {
        $this->repository = $repository;
        $this->requires = collect([]);
    }

    public function require(array|string $name): static
    {
        if (is_array($name)) {
            foreach ($name as $item) {
                $this->require($item);
            }
        }
        if (! $this->requires->has($name)) {
            $library = $this->repository->get($name);
            if (! $library) {
                throw new Exception('Could not locate static library ' . $name);
            }
            foreach ($library->getDependencies() as $dependency) {
                $this->require($dependency);
            }

            $this->requires[$name] = $library;
            foreach ($this->repository->getRequiredWithsFor($library) as $required) {
                $this->require($required->getName());
            }
            Event::dispatch('laravel-static:library-required', [$library, $this]);
        }

        return $this;
    }

    public function required(): Collection
    {
        return $this->requires;
    }

    protected function handleRender(callable $callback): string
    {
        $lines = [];
        $resolver = app(FileResolver::class);
        foreach ($this->requires as $library) {
            $results = $callback($library, $resolver);
            if (! empty($results)) {
                $lines[] = is_array($results) ? implode("\n", $results) : $results;
            }
        }

        return implode("\n", $lines);
    }

    public function scripts(): string
    {
        return $this->handleRender(fn ($library, $resolver) => $library->renderJs($resolver));
    }

    public function styles(): string
    {
        return $this->handleRender(fn ($library, $resolver) => $library->renderStyles($resolver));
    }

    public function isRequired($name): bool
    {
        return $this->requires->has($name);
    }
}
