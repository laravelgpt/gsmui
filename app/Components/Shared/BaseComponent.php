<?php

namespace App\Components\Shared;

use App\Components\Contracts\ComponentInterface;
use App\Components\Contracts\RenderableInterface;

abstract class BaseComponent implements ComponentInterface, RenderableInterface
{
    /** @var string */
    protected string $name;

    /** @var array */
    protected array $config;

    /** @var array */
    protected array $props;

    /** @var array */
    protected array $data;

    /**
     * Constructor
     */
    public function __construct(array $props = [])
    {
        $this->name = $this->getName();
        $this->config = $this->getConfig();
        $this->props = array_merge($this->getDefaultProps(), $props);
        $this->validateProps($this->props);
        $this->data = $this->prepareData();
    }

    /**
     * Prepare component data
     */
    protected function prepareData(): array
    {
        return $this->renderData();
    }

    /**
     * Get component name
     */
    public function getName(): string
    {
        return $this->name ?? str(class_basename(static::class))->kebab()->toString();
    }

    /**
     * Get component config
     */
    public function getConfig(): array
    {
        return [
            'stack' => 'universal',
            'theme' => 'default',
            'variant' => 'base',
            'responsive' => true,
            'interactive' => true,
        ];
    }

    /**
     * Get default props
     */
    public function getDefaultProps(): array
    {
        return [];
    }

    /**
     * Validate component props
     */
    public function validateProps(array $props): bool
    {
        // Override in child classes
        return true;
    }

    /**
     * Render component data
     */
    abstract public function renderData(): array;

    /**
     * Render component for specific stack
     */
    public function render(string $stack = 'blade'): string
    {
        $props = $this->getProps($stack);
        $data = $this->toArray();

        return match($stack) {
            'blade' => $this->renderBlade($props, $data),
            'livewire' => $this->renderLivewire($props, $data),
            'filament' => $this->renderFilament($props, $data),
            'react' => $this->renderReact($props, $data),
            'vue' => $this->renderVue($props, $data),
            default => throw new \Exception("Unsupported stack: {$stack}"),
        };
    }

    /**
     * Get props for stack
     */
    public function getProps(string $stack): array
    {
        $props = $this->props;

        return match($stack) {
            'react' => $this->transformForReact($props),
            'vue' => $this->transformForVue($props),
            default => $props,
        };
    }

    /**
     * Get renderable data
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'config' => $this->config,
            'props' => $this->props,
            'data' => $this->data,
        ];
    }

    /**
     * Render Blade component
     */
    protected function renderBlade(array $props, array $data): string
    {
        $view = "components.{$this->name}";
        return view($view, $props)->render();
    }

    /**
     * Render Livewire component
     */
    protected function renderLivewire(array $props, array $data): string
    {
        $component = "livewire.{$this->name}";
        $propsString = collect($props)->map(fn($v, $k) => "{$k}=\"{$v}\"")->implode(' ');
        return "<livewire:{$this->name} {$propsString} />";
    }

    /**
     * Render Filament component
     */
    protected function renderFilament(array $props, array $data): string
    {
        // Filament uses classes, not string rendering
        return json_encode(['component' => $this->name, 'props' => $props]);
    }

    /**
     * Render React component
     */
    protected function renderReact(array $props, array $data): string
    {
        $propsJson = json_encode($props);
        return "<{$this->reactComponentName()} {...{$propsJson}} />";
    }

    /**
     * Render Vue component
     */
    protected function renderVue(array $props, array $data): string
    {
        $propsJson = json_encode($props);
        return "<{$this->vueComponentName()} v-bind='{$propsJson}' />";
    }

    /**
     * Transform props for React
     */
    protected function transformForReact(array $props): array
    {
        return collect($props)->mapWithKeys(function ($value, $key) {
            $reactKey = str($key)->camel()->toString();
            return [$reactKey => $value];
        })->toArray();
    }

    /**
     * Transform props for Vue
     */
    protected function transformForVue(array $props): array
    {
        return collect($props)->mapWithKeys(function ($value, $key) {
            $vueKey = str($key)->camel()->toString();
            return [$vueKey => $value];
        })->toArray();
    }

    /**
     * Get React component name
     */
    protected function reactComponentName(): string
    {
        return str($this->name)->studly()->replace(' ', '') . 'Component';
    }

    /**
     * Get Vue component name
     */
    protected function vueComponentName(): string
    {
        return str($this->name)->camel()->toString();
    }

    /**
     * Get computed properties
     */
    protected function computed(): array
    {
        return [];
    }

    /**
     * Get watchers
     */
    protected function watchers(): array
    {
        return [];
    }

    /**
     * Get methods
     */
    protected function methods(): array
    {
        return [];
    }
}
