
<?php

namespace App\Components\Shared;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Component Registry
 * Manages all registered components across different stacks
 */
class ComponentRegistry
{
    /** @var array */
    protected static array $components = [];

    /** @var array */
    protected static array $stackComponents = [
        'blade' => [],
        'livewire' => [],
        'filament' => [],
        'react' => [],
        'vue' => [],
    ];

    /**
     * Register a component
     */
    public static function register(string $name, string $class, string $stack = 'universal'): void
    {
        if (!isset(self::$components[$name])) {
            self::$components[$name] = [];
        }

        self::$components[$name][$stack] = $class;

        if ($stack !== 'universal') {
            self::$stackComponents[$stack][$name] = $class;
        }

        self::clearCache();
    }

    /**
     * Get component class for a specific stack
     */
    public static function get(string $name, string $stack = 'blade'): ?string
    {
        // Check stack-specific
        if (isset(self::$stackComponents[$stack][$name])) {
            return self::$stackComponents[$stack][$name];
        }

        // Check universal
        if (isset(self::$components[$name]['universal'])) {
            return self::$components[$name]['universal'];
        }

        // Check first available
        if (isset(self::$components[$name])) {
            return reset(self::$components[$name]);
        }

        return null;
    }

    /**
     * Check if component exists
     */
    public static function has(string $name, string $stack = null): bool
    {
        if ($stack) {
            return isset(self::$stackComponents[$stack][$name]) ||
                   isset(self::$components[$name][$stack]) ||
                   isset(self::$components[$name]['universal']);
        }

        return isset(self::$components[$name]);
    }

    /**
     * Get all components
     */
    public static function all(string $stack = null): array
    {
        if ($stack) {
            return self::$stackComponents[$stack] ?? [];
        }

        return self::$components;
    }

    /**
     * Get components by category
     */
    public static function byCategory(string $category, string $stack = null): array
    {
        $components = self::all($stack);
        $filtered = [];

        foreach ($components as $name => $class) {
            if (is_array($class)) {
                $instance = self::resolve($name, $stack);
            } else {
                $instance = new $class();
            }

            if ($instance && method_exists($instance, 'getConfig')) {
                $config = $instance->getConfig();
                if (($config['category'] ?? '') === $category) {
                    $filtered[$name] = $class;
                }
            }
        }

        return $filtered;
    }

    /**
     * Resolve component instance
     */
    public static function resolve(string $name, string $stack = 'blade', array $props = [])
    {
        $class = self::get($name, $stack);

        if (!$class) {
            throw new \Exception("Component [{$name}] not found for stack [{$stack}]");
        }

        return new $class($props);
    }

    /**
     * Render component
     */
    public static function render(string $name, array $props = [], string $stack = 'blade'): string
    {
        $instance = self::resolve($name, $stack, $props);
        return $instance->render($stack);
    }

    /**
     * Register multiple components at once
     */
    public static function registerMany(array $components): void
    {
        foreach ($components as $name => $config) {
            $class = $config['class'] ?? $config;
            $stack = $config['stack'] ?? 'universal';
            self::register($name, $class, $stack);
        }
    }

    /**
     * Get component tree for navigation
     */
    public static function getTree(string $stack = null): array
    {
        $components = self::all($stack);
        $tree = [];

        foreach ($components as $name => $class) {
            if (is_array($class)) {
                $instance = self::resolve($name, $stack);
            } else {
                $instance = new $class();
            }

            $config = $instance->getConfig();
            $category = $config['category'] ?? 'General';

            if (!isset($tree[$category])) {
                $tree[$category] = [];
            }

            $tree[$category][$name] = [
                'name' => $name,
                'class' => $class,
                'config' => $config,
            ];
        }

        ksort($tree);

        return $tree;
    }

    /**
     * Clear component cache
     */
    public static function clearCache(): void
    {
        Cache::forget('component_registry');
    }

    /**
     * Cache component registry
     */
    public static function cache(): void
    {
        Cache::forever('component_registry', self::$components);
    }

    /**
     * Load from cache
     */
    public static function loadFromCache(): void
    {
        if (Cache::has('component_registry')) {
            self::$components = Cache::get('component_registry', []);
        }
    }

    /**
     * Get available stacks
     */
    public static function getStacks(): array
    {
        return array_keys(self::$stackComponents);
    }

    /**
     * Validate component props for stack
     */
    public static function validateForStack(string $name, array $props, string $stack): bool
    {
        $instance = self::resolve($name, $stack);
        return $instance->validateProps($props);
    }

    /**
     * Get default props for component
     */
    public static function getDefaultProps(string $name, string $stack = 'blade'): array
    {
        $instance = self::resolve($name, $stack);
        return $instance->getDefaultProps();
    }
}
