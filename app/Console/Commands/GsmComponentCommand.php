
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GsmComponentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gsm:component 
                            {name : The name of the component (e.g., "Button")} 
                            {--category=utilities : The component category (data-display, forms, navigation, feedback, layout, media, utilities)} 
                            {--variant=primary : The default variant (primary, danger, ghost)} 
                            {--size=md : The default size (sm, md, lg)} 
                            {--type=all : Type to generate (blade, volt, react, vue, all)} 
                            {--force : Overwrite existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new GSM-UI component across all technology stacks';

    /**
     * Component categories and their paths
     */
    protected $categories = [
        'data-display' => 'Data Display',
        'forms' => 'Forms',
        'navigation' => 'Navigation',
        'feedback' => 'Feedback',
        'layout' => 'Layout',
        'media' => 'Media',
        'utilities' => 'Utilities',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $category = $this->option('category');
        $variant = $this->option('variant');
        $size = $this->option('size');
        $type = $this->option('type');
        $force = $this->option('force');

        // Validate category
        if (!array_key_exists($category, $this->categories)) {
            $this->error("Invalid category: {$category}");
            $this->info('Available categories: ' . implode(', ', array_keys($this->categories)));
            return self::FAILURE;
        }

        // Validate variant
        $validVariants = ['primary', 'danger', 'ghost'];
        if (!in_array($variant, $validVariants)) {
            $this->error("Invalid variant: {$variant}");
            $this->info('Available variants: ' . implode(', ', $validVariants));
            return self::FAILURE;
        }

        // Validate size
        $validSizes = ['sm', 'md', 'lg'];
        if (!in_array($size, $validSizes)) {
            $this->error("Invalid size: {$size}");
            $this->info('Available sizes: ' . implode(', ', $validSizes));
            return self::FAILURE;
        }

        // Generate slug
        $slug = Str::slug($name, '-');
        $studlyName = Str::studly($name);
        $kebabName = Str::kebab($name);
        $snakeName = Str::snake($name);
        $camelName = Str::camel($name);

        $this->info("🎨 Creating component: {$studlyName}");
        $this->info("📁 Category: {$this->categories[$category]}");
        $this->info("🎯 Variant: {$variant}");
        $this->info("📏 Size: {$size}");

        $created = [];

        // Generate Blade component
        if ($type === 'all' || $type === 'blade') {
            if ($this->createBladeComponent($studlyName, $slug, $kebabName, $category, $variant, $size, $force)) {
                $created[] = "Blade: resources/views/components/blade/{$category}/{$slug}.blade.php";
            }
        }

        // Generate Livewire Volt component
        if ($type === 'all' || $type === 'volt') {
            if ($this->createVoltComponent($studlyName, $slug, $kebabName, $category, $variant, $size, $force)) {
                $created[] = "Volt: app/Components/Livewire/Volt/{$studlyName}.php";
            }
        }

        // Generate React component
        if ($type === 'all' || $type === 'react') {
            if ($this->createReactComponent($studlyName, $slug, $kebabName, $category, $variant, $size, $force)) {
                $created[] = "React: app/Components/React/components/{$studlyName}.jsx";
            }
        }

        // Generate Vue component
        if ($type === 'all' || $type === 'vue') {
            if ($this->createVueComponent($studlyName, $slug, $kebabName, $category, $variant, $size, $force)) {
                $created[] = "Vue: app/Components/Vue/components/{$studlyName}.vue";
            }
        }

        // Generate stub
        if ($this->createStub($studlyName, $slug, $category, $force)) {
            $created[] = "Stub: stubs/{$slug}.stub";
        }

        // Generate documentation
        if ($this->createDocumentation($studlyName, $slug, $kebabName, $category, $variant, $size, $force)) {
            $created[] = "Docs: resources/views/components/docs/{$slug}.md";
        }

        // Summary
        $this->info("\n✅ Component created successfully!");
        $this->info("\n📋 Files created:");
        foreach ($created as $file) {
            $this->info("   ➤ {$file}");
        }

        $this->info("\n💡 Usage examples:");
        $this->info("   Blade: <x-components.blade.{$category}.{$slug} />");
        $this->info("   Volt: <livewire:{$studlyName} />");
        $this->info("   React: import {$studlyName} from './components/{$studlyName}';");
        $this->info("   Vue: import {$studlyName} from './components/{$studlyName}.vue';");

        return self::SUCCESS;
    }

    /**
     * Create Blade component
     */
    protected function createBladeComponent($name, $slug, $kebabName, $category, $variant, $size, $force)
    {
        $path = resource_path("views/components/blade/{$category}/{$slug}.blade.php");

        if (File::exists($path) && !$force) {
            $this->error("Blade component already exists: {$path}");
            return false;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = $this->getBladeTemplate($name, $slug, $kebabName, $category, $variant, $size);
        File::put($path, $content);

        $this->info("   ✅ Blade component created: {$path}");
        return true;
    }

    /**
     * Create Livewire Volt component
     */
    protected function createVoltComponent($name, $slug, $kebabName, $category, $variant, $size, $force)
    {
        $path = app_path("Components/Livewire/Volt/{$name}.php");

        if (File::exists($path) && !$force) {
            $this->error("Volt component already exists: {$path}");
            return false;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = $this->getVoltTemplate($name, $slug, $kebabName, $category, $variant, $size);
        File::put($path, $content);

        $this->info("   ✅ Volt component created: {$path}");
        return true;
    }

    /**
     * Create React component
     */
    protected function createReactComponent($name, $slug, $kebabName, $category, $variant, $size, $force)
    {
        $path = app_path("Components/React/components/{$name}.jsx");

        if (File::exists($path) && !$force) {
            $this->error("React component already exists: {$path}");
            return false;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = $this->getReactTemplate($name, $slug, $kebabName, $category, $variant, $size);
        File::put($path, $content);

        $this->info("   ✅ React component created: {$path}");
        return true;
    }

    /**
     * Create Vue component
     */
    protected function createVueComponent($name, $slug, $kebabName, $category, $variant, $size, $force)
    {
        $path = app_path("Components/Vue/components/{$name}.vue");

        if (File::exists($path) && !$force) {
            $this->error("Vue component already exists: {$path}");
            return false;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = $this->getVueTemplate($name, $slug, $kebabName, $category, $variant, $size);
        File::put($path, $content);

        $this->info("   ✅ Vue component created: {$path}");
        return true;
    }

    /**
     * Create stub file
     */
    protected function createStub($name, $slug, $category, $force)
    {
        $path = base_path("stubs/{$slug}.stub");

        if (File::exists($path) && !$force) {
            $this->error("Stub already exists: {$path}");
            return false;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = $this->getStubTemplate($name, $slug, $category);
        File::put($path, $content);

        $this->info("   ✅ Stub created: {$path}");
        return true;
    }

    /**
     * Create documentation
     */
    protected function createDocumentation($name, $slug, $kebabName, $category, $variant, $size, $force)
    {
        $path = resource_path("views/components/docs/{$slug}.md");

        if (File::exists($path) && !$force) {
            $this->error("Documentation already exists: {$path}");
            return false;
        }

        File::ensureDirectoryExists(dirname($path));

        $content = $this->getDocumentationTemplate($name, $slug, $kebabName, $category, $variant, $size);
        File::put($path, $content);

        $this->info("   ✅ Documentation created: {$path}");
        return true;
    }

    /**
     * Get Blade template
     */
    protected function getBladeTemplate($name, $slug, $kebabName, $category, $variant, $size)
    {
        $variants = ['primary' => 'electric-blue', 'danger' => 'red-500', 'ghost' => 'transparent'];
        $variantClass = $variants[$variant] ?? 'electric-blue';

        return <<< 'BLADE'
@props([
    'label' => 'Button',
    'variant' => '{$variant}',
    'size' => '{$size}',
    'icon' => null,
    'iconPosition' => 'left',
    'loading' => false,
    'disabled' => false,
    'fullWidth' => false,
    'type' => 'button',
    'href' => null,
])

@php
    $variantClasses = [
        'primary' => 'bg-[var(--electric-blue)] hover:bg-[var(--electric-blue-dark)] text-white shadow-[0_0_20px_rgba(0,212,255,0.5)]',
        'danger' => 'bg-red-500 hover:bg-red-600 text-white shadow-[0_0_20px_rgba(239,68,68,0.5)]',
        'ghost' => 'bg-white/10 hover:bg-white/20 text-white border border-white/20',
    ];

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm h-8',
        'md' => 'px-4 py-2 text-base h-10',
        'lg' => 'px-6 py-3 text-lg h-12',
    ];

    $class = "gsm-button gsm-button-{$slug} relative overflow-hidden rounded-lg font-medium transition-all duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--electric-blue)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--deep-space)] {$variantClasses[$variant]} {$sizeClasses[$size]} " . ($disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '') . " " . ($fullWidth ? 'w-full' : '');
@endphp

@if ($href)
    <a href="{{ $href }}" class="{{ $class }}" @if($disabled) aria-disabled="true" @endif>
@else
    <button type="{{ $type }}" class="{{ $class }}" @if($disabled) disabled @endif>
@endif

    @if ($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif

    @if ($icon && $iconPosition === 'left')
        <span class="inline-flex items-center">
            <x-icons.{{ $icon }} class="mr-2 h-4 w-4" />
        </span>
    @endif

    <span class="relative z-10">{{ $label }}</span>

    @if ($icon && $iconPosition === 'right')
        <span class="inline-flex items-center">
            <x-icons.{{ $icon }} class="ml-2 h-4 w-4" />
        </span>
    @endif

    <span class="absolute inset-0 overflow-hidden">
        <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full hover:translate-x-full transition-transform duration-700"></span>
    </span>

@if ($href)
    </a>
@else
    </button>
@endif
BLADE;
    }

    /**
     * Get Volt template
     */
    protected function getVoltTemplate($name, $slug, $kebabName, $category, $variant, $size)
    {
        return <<< 'VOLT'
<?php

use function Livewire\Volt\set;

set('label', 'Button');
set('variant', '{$variant}');
set('size', '{$size}');
set('icon', null);
set('iconPosition', 'left');
set('loading', false);
set('disabled', false);
set('fullWidth', false);
set('type', 'button');
set('href', null);

?>

<div>
    @props([
        'label' => null,
        'variant' => null,
        'size' => null,
        'icon' => null,
        'iconPosition' => null,
        'loading' => null,
        'disabled' => null,
        'fullWidth' => null,
        'type' => null,
        'href' => null,
    ])

    @php
        $label = $label ?: get('label');
        $variant = $variant ?: get('variant');
        $size = $size ?: get('size');
        $icon = $icon ?: get('icon');
        $iconPosition = $iconPosition ?: get('iconPosition');
        $loading = $loading ?: get('loading');
        $disabled = $disabled ?: get('disabled');
        $fullWidth = $fullWidth ?: get('fullWidth');
        $type = $type ?: get('type');
        $href = $href ?: get('href');

        $variantClasses = [
            'primary' => 'bg-[var(--electric-blue)] hover:bg-[var(--electric-blue-dark)] text-white shadow-[0_0_20px_rgba(0,212,255,0.5)]',
            'danger' => 'bg-red-500 hover:bg-red-600 text-white shadow-[0_0_20px_rgba(239,68,68,0.5)]',
            'ghost' => 'bg-white/10 hover:bg-white/20 text-white border border-white/20',
        ];

        $sizeClasses = [
            'sm' => 'px-3 py-1.5 text-sm h-8',
            'md' => 'px-4 py-2 text-base h-10',
            'lg' => 'px-6 py-3 text-lg h-12',
        ];

        $class = "gsm-button relative overflow-hidden rounded-lg font-medium transition-all duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--electric-blue)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--deep-space)] {$variantClasses[$variant]} {$sizeClasses[$size]} " . ($disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '') . " " . ($fullWidth ? 'w-full' : '');
    @endphp

    @if ($href)
        <a href="{{ $href }}" class="{{ $class }}" @if($disabled) aria-disabled="true" @endif>
    @else
        <button type="{{ $type }}" class="{{ $class }}" @if($disabled) disabled @endif>
    @endif

        @if ($loading)
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        @endif

        @if ($icon && $iconPosition === 'left')
            <span class="inline-flex items-center">
                <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
            </span>
        @endif

        <span class="relative z-10">{{ $label }}</span>

        @if ($icon && $iconPosition === 'right')
            <span class="inline-flex items-center">
                <svg class="ml-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"></path>
                </svg>
            </span>
        @endif

        <span class="absolute inset-0 overflow-hidden">
            <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full hover:translate-x-full transition-transform duration-700"></span>
        </span>

    @if ($href)
        </a>
    @else
        </button>
    @endif
</div>
VOLT;
    }

    /**
     * Get React template
     */
    protected function getReactTemplate($name, $slug, $kebabName, $category, $variant, $size)
    {
        return <<< 'REACT'
import React from 'react';
import PropTypes from 'prop-types';

const {$name} = ({
    label = 'Button',
    variant = '{$variant}',
    size = '{$size}',
    icon = null,
    iconPosition = 'left',
    loading = false,
    disabled = false,
    fullWidth = false,
    type = 'button',
    href = null,
    onClick,
    className = '',
}) => {
    const variantClasses = {
        primary: 'bg-[var(--electric-blue)] hover:bg-[var(--electric-blue-dark)] text-white shadow-[0_0_20px_rgba(0,212,255,0.5)]',
        danger: 'bg-red-500 hover:bg-red-600 text-white shadow-[0_0_20px_rgba(239,68,68,0.5)]',
        ghost: 'bg-white/10 hover:bg-white/20 text-white border border-white/20',
    };

    const sizeClasses = {
        sm: 'px-3 py-1.5 text-sm h-8',
        md: 'px-4 py-2 text-base h-10',
        lg: 'px-6 py-3 text-lg h-12',
    };

    const classNames = `gsm-button gsm-button-{$slug} relative overflow-hidden rounded-lg font-medium transition-all duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--electric-blue)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--deep-space)] ${
        variantClasses[variant]
    } ${
        sizeClasses[size]
    } ${
        disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''
    } ${
        fullWidth ? 'w-full' : ''
    } ${
        className
    }`;

    const Icon = icon;

    const content = (
        <>
            {loading && (
                <svg className="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            )}

            {icon && iconPosition === 'left' && (
                <span className="inline-flex items-center">
                    <Icon className="mr-2 h-4 w-4" />
                </span>
            )}

            <span className="relative z-10">{label}</span>

            {icon && iconPosition === 'right' && (
                <span className="inline-flex items-center">
                    <Icon className="ml-2 h-4 w-4" />
                </span>
            )}

            <span className="absolute inset-0 overflow-hidden">
                <span className="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full hover:translate-x-full transition-transform duration-700"></span>
            </span>
        </>
    );

    if (href) {
        return (
            <a href={href} className={classNames} {...(disabled && { 'aria-disabled': true })}>
                {content}
            </a>
        );
    }

    return (
        <button
            type={type}
            className={classNames}
            onClick={onClick}
            disabled={disabled}
        >
            {content}
        </button>
    );
};

{$name}.propTypes = {
    label: PropTypes.string,
    variant: PropTypes.oneOf(['primary', 'danger', 'ghost']),
    size: PropTypes.oneOf(['sm', 'md', 'lg']),
    icon: PropTypes.elementType,
    iconPosition: PropTypes.oneOf(['left', 'right']),
    loading: PropTypes.bool,
    disabled: PropTypes.bool,
    fullWidth: PropTypes.bool,
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    href: PropTypes.string,
    onClick: PropTypes.func,
    className: PropTypes.string,
};

export default {$name};
REACT;
    }

    /**
     * Get Vue template
     */
    protected function getVueTemplate($name, $slug, $kebabName, $category, $variant, $size)
    {
        return <<< 'VUE'
<template>
  <component
    :is="href ? 'a' : 'button'"
    :class="classNames"
    :type="type"
    :href="href"
    :disabled="disabled"
    @click="handleClick"
  >
    <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>

    <span v-if="icon && iconPosition === 'left'" class="inline-flex items-center">
      <component :is="icon" class="mr-2 h-4 w-4" />
    </span>

    <span class="relative z-10">{{ label }}</span>

    <span v-if="icon && iconPosition === 'right'" class="inline-flex items-center">
      <component :is="icon" class="ml-2 h-4 w-4" />
    </span>

    <span class="absolute inset-0 overflow-hidden">
      <span class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full hover:translate-x-full transition-transform duration-700"></span>
    </span>
  </component>
</template>

<script>
import { computed } from 'vue';

export default {
  name: '{$name}',
  props: {
    label: {
      type: String,
      default: 'Button',
    },
    variant: {
      type: String,
      default: '{$variant}',
      validator: (value) => ['primary', 'danger', 'ghost'].includes(value),
    },
    size: {
      type: String,
      default: '{$size}',
      validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    icon: {
      type: [Object, Function],
      default: null,
    },
    iconPosition: {
      type: String,
      default: 'left',
      validator: (value) => ['left', 'right'].includes(value),
    },
    loading: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    fullWidth: {
      type: Boolean,
      default: false,
    },
    type: {
      type: String,
      default: 'button',
      validator: (value) => ['button', 'submit', 'reset'].includes(value),
    },
    href: {
      type: String,
      default: null,
    },
  },
  emits: ['click'],
  setup(props, { emit }) {
    const variantClasses = {
      primary: 'bg-[var(--electric-blue)] hover:bg-[var(--electric-blue-dark)] text-white shadow-[0_0_20px_rgba(0,212,255,0.5)]',
      danger: 'bg-red-500 hover:bg-red-600 text-white shadow-[0_0_20px_rgba(239,68,68,0.5)]',
      ghost: 'bg-white/10 hover:bg-white/20 text-white border border-white/20',
    };

    const sizeClasses = {
      sm: 'px-3 py-1.5 text-sm h-8',
      md: 'px-4 py-2 text-base h-10',
      lg: 'px-6 py-3 text-lg h-12',
    };

    const classNames = computed(() => 
      \`gsm-button gsm-button-{$slug} relative overflow-hidden rounded-lg font-medium transition-all duration-200 ease-in-out focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--electric-blue)] focus-visible:ring-offset-2 focus-visible:ring-offset-[var(--deep-space)] \${
        variantClasses[props.variant]
      } \${
        sizeClasses[props.size]
      } \${
        props.disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : ''
      } \${
        props.fullWidth ? 'w-full' : ''
      }\`
    );

    const handleClick = (event) => {
      if (!props.disabled) {
        emit('click', event);
      }
    };

    return {
      classNames,
      handleClick,
    };
  },
};
</script>
VUE;
    }

    /**
     * Get stub template
     */
    protected function getStubTemplate($name, $slug, $category)
    {
        return <<< 'STUB'
<div class="gsm-button gsm-button-{$slug}"
     x-data="{ label: '{$name}', variant: 'primary', size: 'md', loading: false, disabled: false }"
     x-init="() => { console.log('{$name} component loaded'); }"
>
    <template x-if="!loading">
        <span x-text="label"></span>
    </template>
    <template x-if="loading">
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </template>
</div>
STUB;
    }

    /**
     * Get documentation template
     */
    protected function getDocumentationTemplate($name, $slug, $kebabName, $category, $variant, $size)
    {
        return <<< 'DOCS'
# {$name} Component

## Overview
The **{$name}** component is a {$category} component designed for modern web applications.

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| label | string | 'Button' | Text to display |
| variant | string | '{$variant}' | Visual style (primary, danger, ghost) |
| size | string | '{$size}' | Size variant (sm, md, lg) |
| icon | string | null | Icon name to display |
| iconPosition | string | 'left' | Icon position (left, right) |
| loading | boolean | false | Show loading state |
| disabled | boolean | false | Disable the button |
| fullWidth | boolean | false | Full width button |
| type | string | 'button' | Button type (button, submit, reset) |
| href | string | null | Link URL (makes it an `<a>` tag) |

## Usage

### Blade
```blade
<x-components.blade.{$category}.{$slug} 
    label="Get Started" 
    variant="primary" 
    size="lg" 
    icon="arrow-right"
/>
```

### Livewire Volt
```php
<livewire:{$name}
    label="Get Started"
    variant="primary"
    size="lg"
    icon="arrow-right"
/>
```

### React
```jsx
import {$name} from './components/{$name}';

<{$name}
    label="Get Started"
    variant="primary"
    size="lg"
    icon={ArrowRightIcon}
/>
```

### Vue
```vue
<template>
  <{$name}
    label="Get Started"
    variant="primary"
    size="lg"
    :icon="ArrowRightIcon"
  />
</template>

<script setup>
import {$name} from './components/{$name}.vue';
import { ArrowRightIcon } from './icons';
</script>
```

## Examples

### Primary Button
```blade
<x-components.blade.{$category}.{$slug} label="Primary Action" />
```

### Danger Button
```blade
<x-components.blade.{$category}.{$slug} 
    label="Delete" 
    variant="danger" 
/>
```

### Ghost Button
```blade
<x-components.blade.{$category}.{$slug} 
    label="Cancel" 
    variant="ghost" 
/>
```

## Events

| Event | Description | Data |
|-------|-------------|------|
| click | Emitted when button is clicked | MouseEvent |

## Accessibility
- ✅ WCAG 2.1 AA compliant
- ✅ Keyboard navigable
- ✅ Focus indicators
- ✅ Screen reader support

## Changelog
- v1.0.0 - Initial release
DOCS;
    }
}
