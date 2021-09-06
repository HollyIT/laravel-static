<?php

namespace HollyIT\LaravelStatic;

use HollyIT\LaravelStatic\FileResolvers\FileResolver;

class AssetLibrary
{
    protected string $name;
    protected array $dependencies = [];
    protected array $js = [];
    protected array $css = [];
    protected ?string $publicPath = null;
    protected ?string $manifestPath = null;
    protected ?string $hotFile = null;
    protected array $requiredWith = [];
    /**
     * @var callable|string|null
     */
    protected $renderJsCallback;
    protected $renderCssCallback;

    /**
     * @param  string  $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function create(string $name): static
    {
        return new static($name);
    }

    public function withJs(string $file, array $options = []): static
    {
        $this->js[$file] = $options;

        return $this;
    }

    public function withCss(string $file, array $options = []): static
    {
        if (! array_key_exists('rel', $options)) {
            $options['rel'] = 'stylesheet';
        }
        $this->css[$file] = $options;

        return $this;
    }

    public function renderJsWith(callable | string | null $callbackOrView): static
    {
        $this->renderJsCallback = $callbackOrView;

        return $this;
    }

    public function renderCssWith(callable | string | null $callbackOrView): static
    {
        $this->renderCssCallback = $callbackOrView;

        return $this;
    }

    public function publicPath(string $path): static
    {
        $this->publicPath = $path;

        return $this;
    }

    public function manifestPath(string $path): static
    {
        $this->manifestPath = $path;

        return $this;
    }

    public function hotPath(string $path): static
    {
        $this->hotFile = $path;

        return $this;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    public function requireWith($requiredWith): static
    {
        $requiredWith = is_array($requiredWith) ? $requiredWith : [$requiredWith];
        $this->requiredWith = $requiredWith;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequiredWith(): array
    {
        return $this->requiredWith;
    }

    public function getJs(): array
    {
        return $this->js;
    }

    public function getCss(): array
    {
        return $this->css;
    }

    public function getPublicPath(): ?string
    {
        return $this->publicPath;
    }

    public function getManifestPath(): ?string
    {
        return $this->manifestPath ?? $this->publicPath.'/mix-manifest.json';
    }

    public function getHotFile(): ?string
    {
        return $this->hotFile ?? $this->publicPath.'/hot';
    }

    public function renderJs(FileResolver $resolver): array | string
    {
        $lines = [];

        foreach ($this->js as $file => $options) {
            $src = $resolver->resolve($this, $file);
            $options = ['src' => $src] + $options;
            $lines[] = '<script '.$this->makeAttributes($options).'></script>';
        }

        if ($this->renderJsCallback) {
            if (is_callable($this->renderJsCallback)) {
                return call_user_func($this->renderJsCallback, $lines, $this);
            }

            return (string) view($this->renderJsCallback, [
                'scripts' => $lines,
                'library' => $this,
            ]);
        }

        return $lines;
    }

    public function renderStyles(FileResolver $resolver): array | string
    {
        $lines = [];
        foreach ($this->css as $file => $options) {
            $options = ['href' => $resolver->resolve($this, $file)] + $options;
            $lines[] = '<link '.$this->makeAttributes($options).'>';
        }
        if ($this->renderCssCallback) {
            if (is_callable($this->renderCssCallback)) {
                return call_user_func($this->renderCssCallback, $lines, $this);
            }

            return (string) view($this->renderCssCallback, [
                'links' => $lines,
                'library' => $this,
            ]);
        }

        return $lines;
    }

    protected function makeAttributes(array $options): string
    {
        $lines = [];
        foreach ($options as $key => $value) {
            if (is_numeric($key)) {
                $lines[] = $value;
            } else {
                $lines[] = $key.'="'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8', true).'"';
            }
        }

        return implode(' ', $lines);
    }

    public function dependsOn($dependency): static
    {
        $dependency = is_array($dependency) ? $dependency : [$dependency];
        foreach ($dependency as $dep) {
            $this->dependencies[$dep] = $dep;
        }

        return $this;
    }
}
