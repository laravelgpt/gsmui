
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * The Grid CN Integration Service
 * Integrates components and templates from https://thegridcn.com
 */
class GridCNIntegrationService
{
    /**
     * Base URL for The Grid CN API
     */
    protected $baseUrl = 'https://thegridcn.com/api';

    /**
     * Fetch and integrate all components from The Grid CN
     */
    public function integrateAllComponents()
    {
        $categories = [
            'data-display',
            'forms', 
            'navigation',
            'feedback',
            'layout',
            'media',
            'utilities',
            'advanced',
            'charts',
            'ecommerce'
        ];

        $results = [];

        foreach ($categories as $category) {
            $results[$category] = $this->fetchCategoryComponents($category);
        }

        return $results;
    }

    /**
     * Fetch components from a category
     */
    protected function fetchCategoryComponents($category)
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/components/{$category}");

            if ($response->successful()) {
                $components = $response->json('data', []);
                
                $integrated = [];
                foreach ($components as $component) {
                    $integrated[] = $this->integrateComponent($component, $category);
                }

                return [
                    'success' => true,
                    'category' => $category,
                    'count' => count($integrated),
                    'components' => $integrated
                ];
            }

            return ['success' => false, 'error' => 'Failed to fetch components'];

        } catch (\Exception $e) {
            Log::error("Grid CN integration error for {$category}: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Integrate a single component
     */
    protected function integrateComponent($component, $category)
    {
        $name = $component['name'] ?? 'Component';
        $slug = \Illuminate\Support\Str::slug($name);
        
        $files = [];

        // Generate Blade component
        $bladePath = resource_path("views/components/blade/{$category}/{$slug}.blade.php");
        $bladeContent = $this->generateGridCNBlade($component, $name, $slug);
        \Illuminate\Support\Facades\File::put($bladePath, $bladeContent);
        $files[] = $bladePath;

        // Generate Livewire Volt component
        $voltPath = app_path("Components/Livewire/Volt/{$name}.php");
        $voltContent = $this->generateGridCNVolt($component, $name, $slug);
        \Illuminate\Support\Facades\File::put($voltPath, $voltContent);
        $files[] = $voltPath;

        // Generate React component
        $reactPath = app_path("Components/React/components/{$name}.jsx");
        $reactContent = $this->generateGridCNReact($component, $name, $slug);
        \Illuminate\Support\Facades\File::put($reactPath, $reactContent);
        $files[] = $reactPath;

        // Generate Vue component
        $vuePath = app_path("Components/Vue/components/{$name}.vue");
        $vueContent = $this->generateGridCNVue($component, $name, $slug);
        \Illuminate\Support\Facades\File::put($vuePath, $vueContent);
        $files[] = $vuePath;

        // Generate stub
        $stubPath = base_path("stubs/{$slug}.stub");
        $stubContent = $this->generateGridCNStub($component, $name, $slug);
        \Illuminate\Support\Facades\File::put($stubPath, $stubContent);
        $files[] = $stubPath;

        // Generate documentation
        $docPath = resource_path("views/components/docs/{$slug}.md");
        $docContent = $this->generateGridCNDoc($component, $name, $slug);
        \Illuminate\Support\Facades\File::put($docPath, $docContent);
        $files[] = $docPath;

        return [
            'name' => $name,
            'slug' => $slug,
            'category' => $category,
            'files' => $files
        ];
    }

    /**
     * Generate Blade component for Grid CN style
     */
    protected function generateGridCNBlade($component, $name, $slug)
    {
        $type = $component['type'] ?? 'card';
        $style = $component['style'] ?? 'modern';
        
        return <<< 'BLADE'
@props([
    'title' => '{{ $name }}',
    'subtitle' => null,
    'content' => null,
    'image' => null,
    'icon' => null,
    'variant' => 'default',
    'hover' => true,
    'shadow' => true,
    'rounded' => true,
    'link' => null,
    'target' => '_self',
])

@php
    $variantClasses = [
        'default' => 'bg-white/5 backdrop-blur-xl border border-white/10 hover:border-white/20',
        'primary' => 'bg-gradient-to-br from-[var(--electric-blue)]/20 to-[var(--indigo)]/20 border border-[var(--electric-blue)]/20 hover:border-[var(--electric-blue)]/40',
        'danger' => 'bg-gradient-to-br from-red-500/20 to-red-600/20 border border-red-500/20 hover:border-red-400/40',
        'success' => 'bg-gradient-to-br from-green-500/20 to-emerald-600/20 border border-green-500/20 hover:border-green-400/40',
        'warning' => 'bg-gradient-to-br from-yellow-500/20 to-orange-600/20 border border-yellow-500/20 hover:border-yellow-400/40',
        'dark' => 'bg-[#1a1a2e] border border-white/5 hover:border-white/10',
        'glass' => 'bg-white/10 backdrop-blur-2xl border border-white/20 hover:border-white/30',
    ];

    $shadowClasses = $shadow ? 'shadow-2xl shadow-black/50' : '';
    $roundedClasses = $rounded ? 'rounded-2xl' : 'rounded-none';
    $hoverClasses = $hover ? 'hover:scale-[1.02] hover:shadow-3xl hover:shadow-[var(--electric-blue)]/20 transform transition-all duration-500 ease-out' : '';

    $class = "grid-card relative overflow-hidden {$variantClasses[$variant]} {$shadowClasses} {$roundedClasses} {$hoverClasses}";
@endphp

@if ($link)
    <a href="{{ $link }}" target="{{ $target }}" class="{{ $class }}">
@else
    <div class="{{ $class }}">
@endif

    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -inset-[10px] opacity-50">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[var(--electric-blue)]/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute top-1/3 left-1/3 w-[400px] h-[400px] bg-[var(--toxic-green)]/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-1/3 right-1/3 w-[300px] h-[300px] bg-[var(--indigo)]/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>
    </div>

    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 opacity-10" 
         style="background-image: 
                linear-gradient(rgba(0, 212, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.1) 1px, transparent 1px);
                background-size: 40px 40px;">
    </div>

    <!-- Content Container -->
    <div class="relative z-10 p-6">
        @if ($image || $icon)
            <div class="flex items-center justify-center mb-4">
                @if ($image)
                    <div class="relative">
                        <img src="{{ $image }}" alt="{{ $title }}" 
                             class="w-16 h-16 rounded-xl object-cover border-2 border-[var(--electric-blue)]/30">
                        <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-[var(--electric-blue)] rounded-full border-2 border-[var(--deep-space)] flex items-center justify-center">
                            <svg class="w-3 h-3 text-[var(--deep-space)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                <polyline points="2 17 12 22 22 17"></polyline>
                                <polyline points="2 12 12 17 22 12"></polyline>
                            </svg>
                        </div>
                    </div>
                @elseif ($icon)
                    <div class="w-16 h-16 rounded-xl bg-[var(--electric-blue)]/20 border border-[var(--electric-blue)]/30 flex items-center justify-center">
                        <x-icons.{{ $icon }} class="w-8 h-8 text-[var(--electric-blue)]" />
                    </div>
                @endif
            </div>
        @endif

        @if ($title)
            <h3 class="text-xl font-bold text-white mb-2 glow-blue text-center">{{ $title }}</h3>
        @endif

        @if ($subtitle)
            <p class="text-sm text-gray-400 mb-4 text-center">{{ $subtitle }}</p>
        @endif

        @if ($content)
            <div class="text-gray-300 text-sm leading-relaxed">
                {{ $content }}
            </div>
        @else
            <div class="text-gray-400 text-sm leading-relaxed">
                {{ $slot }}
            </div>
        @endif

        @if ($link)
            <div class="mt-6 flex items-center justify-center">
                <span class="text-[var(--electric-blue)] text-sm font-medium flex items-center gap-2 hover:gap-3 transition-all">
                    Learn More
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        @endif
    </div>

    <!-- Corner Accents -->
    <div class="absolute top-0 left-0 w-10 h-10 border-t-2 border-l-2 border-[var(--electric-blue)]/30"></div>
    <div class="absolute top-0 right-0 w-10 h-10 border-t-2 border-r-2 border-[var(--electric-blue)]/30"></div>
    <div class="absolute bottom-0 left-0 w-10 h-10 border-b-2 border-l-2 border-[var(--electric-blue)]/30"></div>
    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-2 border-r-2 border-[var(--electric-blue)]/30"></div>

@if ($link)
    </a>
@else
    </div>
@endif
BLADE;
    }

    /**
     * Generate Livewire Volt component for Grid CN style
     */
    protected function generateGridCNVolt($component, $name, $slug)
    {
        return <<< 'VOLT'
<?php

use function Livewire\Volt\set;

set('title', '{{ $name }}');
set('subtitle', null);
set('content', null);
set('image', null);
set('icon', null);
set('variant', 'default');
set('hover', true);
set('shadow', true);
set('rounded', true);
set('link', null);
set('target', '_self');

?>

<div>
    @props([
        'title' => null,
        'subtitle' => null,
        'content' => null,
        'image' => null,
        'icon' => null,
        'variant' => null,
        'hover' => null,
        'shadow' => null,
        'rounded' => null,
        'link' => null,
        'target' => null,
    ])

    @php
        $variantClasses = [
            'default' => 'bg-white/5 backdrop-blur-xl border border-white/10 hover:border-white/20',
            'primary' => 'bg-gradient-to-br from-[var(--electric-blue)]/20 to-[var(--indigo)]/20 border border-[var(--electric-blue)]/20 hover:border-[var(--electric-blue)]/40',
            'danger' => 'bg-gradient-to-br from-red-500/20 to-red-600/20 border border-red-500/20 hover:border-red-400/40',
            'success' => 'bg-gradient-to-br from-green-500/20 to-emerald-600/20 border border-green-500/20 hover:border-green-400/40',
            'warning' => 'bg-gradient-to-br from-yellow-500/20 to-orange-600/20 border border-yellow-500/20 hover:border-yellow-400/40',
            'dark' => 'bg-[#1a1a2e] border border-white/5 hover:border-white/10',
            'glass' => 'bg-white/10 backdrop-blur-2xl border border-white/20 hover:border-white/30',
        ];

        $shadowClasses = $shadow ? 'shadow-2xl shadow-black/50' : '';
        $roundedClasses = $rounded ? 'rounded-2xl' : 'rounded-none';
        $hoverClasses = $hover ? 'hover:scale-[1.02] hover:shadow-3xl hover:shadow-[var(--electric-blue)]/20 transform transition-all duration-500 ease-out' : '';

        $class = "grid-card relative overflow-hidden {$variantClasses[$variant]} {$shadowClasses} {$roundedClasses} {$hoverClasses}";
    @endphp

    @if ($link)
        <a href="{{ $link }}" target="{{ $target }}" class="{{ $class }}">
    @else
        <div class="{{ $class }}">
    @endif

        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -inset-[10px] opacity-50">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[var(--electric-blue)]/10 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute top-1/3 left-1/3 w-[400px] h-[400px] bg-[var(--toxic-green)]/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute bottom-1/3 right-1/3 w-[300px] h-[300px] bg-[var(--indigo)]/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 2s;"></div>
            </div>
        </div>

        <!-- Grid Pattern -->
        <div class="absolute inset-0 opacity-10" 
             style="background-image: 
                    linear-gradient(rgba(0, 212, 255, 0.1) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(0, 212, 255, 0.1) 1px, transparent 1px);
                    background-size: 40px 40px;">
        </div>

        <div class="relative z-10 p-6">
            @if ($image || $icon)
                <div class="flex items-center justify-center mb-4">
                    @if ($image)
                        <div class="relative">
                            <img src="{{ $image }}" alt="{{ $title }}" 
                                 class="w-16 h-16 rounded-xl object-cover border-2 border-[var(--electric-blue)]/30">
                            <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-[var(--electric-blue)] rounded-full border-2 border-[var(--deep-space)] flex items-center justify-center">
                                <svg class="w-3 h-3 text-[var(--deep-space)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                    <polyline points="2 17 12 22 22 17"></polyline>
                                    <polyline points="2 12 12 17 22 12"></polyline>
                                </svg>
                            </div>
                        </div>
                    @elseif ($icon)
                        <div class="w-16 h-16 rounded-xl bg-[var(--electric-blue)]/20 border border-[var(--electric-blue)]/30 flex items-center justify-center">
                            <svg class="w-8 h-8 text-[var(--electric-blue)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                <polyline points="2 17 12 22 22 17"></polyline>
                                <polyline points="2 12 12 17 22 12"></polyline>
                            </svg>
                        </div>
                    @endif
                </div>
            @endif

            @if ($title)
                <h3 class="text-xl font-bold text-white mb-2 glow-blue text-center">{{ $title }}</h3>
            @endif

            @if ($subtitle)
                <p class="text-sm text-gray-400 mb-4 text-center">{{ $subtitle }}</p>
            @endif

            @if ($content)
                <div class="text-gray-300 text-sm leading-relaxed">
                    {{ $content }}
                </div>
            @else
                <div class="text-gray-400 text-sm leading-relaxed">
                    {{ $slot }}
                </div>
            @endif

            @if ($link)
                <div class="mt-6 flex items-center justify-center">
                    <span class="text-[var(--electric-blue)] text-sm font-medium flex items-center gap-2 hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </span>
                </div>
            @endif
        </div>

        <!-- Corner Accents -->
        <div class="absolute top-0 left-0 w-10 h-10 border-t-2 border-l-2 border-[var(--electric-blue)]/30"></div>
        <div class="absolute top-0 right-0 w-10 h-10 border-t-2 border-r-2 border-[var(--electric-blue)]/30"></div>
        <div class="absolute bottom-0 left-0 w-10 h-10 border-b-2 border-l-2 border-[var(--electric-blue)]/30"></div>
        <div class="absolute bottom-0 right-0 w-10 h-10 border-b-2 border-r-2 border-[var(--electric-blue)]/30"></div>

    @if ($link)
        </a>
    @else
        </div>
    @endif
</div>
VOLT;
    }

    /**
     * Generate React component for Grid CN style
     */
    protected function generateGridCNReact($component, $name, $slug)
    {
        return <<< 'REACT'
import React from 'react';
import PropTypes from 'prop-types';

const {$name} = ({
    title = '{$name}',
    subtitle = null,
    content = null,
    image = null,
    icon: Icon = null,
    variant = 'default',
    hover = true,
    shadow = true,
    rounded = true,
    link = null,
    target = '_self',
    children,
    className = '',
}) => {
    const variantClasses = {
        default: 'bg-white/5 backdrop-blur-xl border border-white/10 hover:border-white/20',
        primary: 'bg-gradient-to-br from-[var(--electric-blue)]/20 to-[var(--indigo)]/20 border border-[var(--electric-blue)]/20 hover:border-[var(--electric-blue)]/40',
        danger: 'bg-gradient-to-br from-red-500/20 to-red-600/20 border border-red-500/20 hover:border-red-400/40',
        success: 'bg-gradient-to-br from-green-500/20 to-emerald-600/20 border border-green-500/20 hover:border-green-400/40',
        warning: 'bg-gradient-to-br from-yellow-500/20 to-orange-600/20 border border-yellow-500/20 hover:border-yellow-400/40',
        dark: 'bg-[#1a1a2e] border border-white/5 hover:border-white/10',
        glass: 'bg-white/10 backdrop-blur-2xl border border-white/20 hover:border-white/30',
    };

    const shadowClasses = shadow ? 'shadow-2xl shadow-black/50' : '';
    const roundedClasses = rounded ? 'rounded-2xl' : 'rounded-none';
    const hoverClasses = hover
        ? 'hover:scale-[1.02] hover:shadow-3xl hover:shadow-[var(--electric-blue)]/20 transform transition-all duration-500 ease-out'
        : '';

    const classNames = `grid-card relative overflow-hidden ${
        variantClasses[variant]
    } ${shadowClasses} ${roundedClasses} ${hoverClasses} ${className}`;

    const Component = link ? 'a' : 'div';
    const props = link ? { href: link, target, rel: 'noopener noreferrer' } : {};

    return (
        <Component className={classNames} {...props}>
            {/* Animated Background */}
            <div className="absolute inset-0 overflow-hidden pointer-events-none">
                <div className="absolute -inset-[10px] opacity-50">
                    <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[var(--electric-blue)]/10 rounded-full blur-3xl animate-pulse"></div>
                    <div className="absolute top-1/3 left-1/3 w-[400px] h-[400px] bg-[var(--toxic-green)]/10 rounded-full blur-2xl animate-pulse" style={{ animationDelay: '1s' }}></div>
                    <div className="absolute bottom-1/3 right-1/3 w-[300px] h-[300px] bg-[var(--indigo)]/10 rounded-full blur-2xl animate-pulse" style={{ animationDelay: '2s' }}></div>
                </div>
            </div>

            {/* Grid Pattern */}
            <div
                className="absolute inset-0 opacity-10"
                style={{
                    backgroundImage: `
                        linear-gradient(rgba(0, 212, 255, 0.1) 1px, transparent 1px),
                        linear-gradient(90deg, rgba(0, 212, 255, 0.1) 1px, transparent 1px)
                    `,
                    backgroundSize: '40px 40px',
                }}
            ></div>

            <div className="relative z-10 p-6">
                {(image || Icon) && (
                    <div className="flex items-center justify-center mb-4">
                        {image ? (
                            <div className="relative">
                                <img
                                    src={image}
                                    alt={title}
                                    className="w-16 h-16 rounded-xl object-cover border-2 border-[var(--electric-blue)]/30"
                                />
                                <div className="absolute -bottom-2 -right-2 w-6 h-6 bg-[var(--electric-blue)] rounded-full border-2 border-[var(--deep-space)] flex items-center justify-center">
                                    <svg className="w-3 h-3 text-[var(--deep-space)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                        <polyline points="2 17 12 22 22 17"></polyline>
                                        <polyline points="2 12 12 17 22 12"></polyline>
                                    </svg>
                                </div>
                            </div>
                        ) : (
                            <div className="w-16 h-16 rounded-xl bg-[var(--electric-blue)]/20 border border-[var(--electric-blue)]/30 flex items-center justify-center">
                                <Icon className="w-8 h-8 text-[var(--electric-blue)]" />
                            </div>
                        )}
                    </div>
                )}

                {title && (
                    <h3 className="text-xl font-bold text-white mb-2 glow-blue text-center">
                        {title}
                    </h3>
                )}

                {subtitle && (
                    <p className="text-sm text-gray-400 mb-4 text-center">{subtitle}</p>
                )}

                {content ? (
                    <div className="text-gray-300 text-sm leading-relaxed">{content}</div>
                ) : (
                    <div className="text-gray-400 text-sm leading-relaxed">{children}</div>
                )}

                {link && (
                    <div className="mt-6 flex items-center justify-center">
                        <span className="text-[var(--electric-blue)] text-sm font-medium flex items-center gap-2 hover:gap-3 transition-all">
                            Learn More
                            <svg className="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                )}
            </div>

            {/* Corner Accents */}
            <div className="absolute top-0 left-0 w-10 h-10 border-t-2 border-l-2 border-[var(--electric-blue)]/30"></div>
            <div className="absolute top-0 right-0 w-10 h-10 border-t-2 border-r-2 border-[var(--electric-blue)]/30"></div>
            <div className="absolute bottom-0 left-0 w-10 h-10 border-b-2 border-l-2 border-[var(--electric-blue)]/30"></div>
            <div className="absolute bottom-0 right-0 w-10 h-10 border-b-2 border-r-2 border-[var(--electric-blue)]/30"></div>
        </Component>
    );
};

{$name}.propTypes = {
    title: PropTypes.string,
    subtitle: PropTypes.string,
    content: PropTypes.node,
    image: PropTypes.string,
    icon: PropTypes.elementType,
    variant: PropTypes.oneOf(['default', 'primary', 'danger', 'success', 'warning', 'dark', 'glass']),
    hover: PropTypes.bool,
    shadow: PropTypes.bool,
    rounded: PropTypes.bool,
    link: PropTypes.string,
    target: PropTypes.string,
    children: PropTypes.node,
    className: PropTypes.string,
};

export default {$name};
REACT;
    }

    /**
     * Generate Vue component for Grid CN style
     */
    protected function generateGridCNVue($component, $name, $slug)
    {
        return <<< 'VUE'
<template>
  <component
    :is="link ? 'a' : 'div'"
    :class="classNames"
    :href="link"
    :target="target"
  >
    <!-- Animated Background -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -inset-[10px] opacity-50">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[var(--electric-blue)]/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute top-1/3 left-1/3 w-[400px] h-[400px] bg-[var(--toxic-green)]/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-1/3 right-1/3 w-[300px] h-[300px] bg-[var(--indigo)]/10 rounded-full blur-2xl animate-pulse" style="animation-delay: 2s;"></div>
      </div>
    </div>

    <!-- Grid Pattern -->
    <div
      class="absolute inset-0 opacity-10"
      :style="{
        backgroundImage: `
          linear-gradient(rgba(0, 212, 255, 0.1) 1px, transparent 1px),
          linear-gradient(90deg, rgba(0, 212, 255, 0.1) 1px, transparent 1px)
        `,
        backgroundSize: '40px 40px',
      }"
    ></div>

    <div class="relative z-10 p-6">
      <!-- Image or Icon -->
      <div v-if="image || icon" class="flex items-center justify-center mb-4">
        <div v-if="image" class="relative">
          <img :src="image" :alt="title" class="w-16 h-16 rounded-xl object-cover border-2 border-[var(--electric-blue)]/30" />
          <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-[var(--electric-blue)] rounded-full border-2 border-[var(--deep-space)] flex items-center justify-center">
            <svg class="w-3 h-3 text-[var(--deep-space)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
              <polyline points="2 17 12 22 22 17"></polyline>
              <polyline points="2 12 12 17 22 12"></polyline>
            </svg>
          </div>
        </div>
        <div v-else-if="icon" class="w-16 h-16 rounded-xl bg-[var(--electric-blue)]/20 border border-[var(--electric-blue)]/30 flex items-center justify-center">
          <component :is="icon" class="w-8 h-8 text-[var(--electric-blue)]" />
        </div>
      </div>

      <!-- Title -->
      <h3 v-if="title" class="text-xl font-bold text-white mb-2 glow-blue text-center">{{ title }}</h3>

      <!-- Subtitle -->
      <p v-if="subtitle" class="text-sm text-gray-400 mb-4 text-center">{{ subtitle }}</p>

      <!-- Content -->
      <div v-if="content" class="text-gray-300 text-sm leading-relaxed" v-html="content"></div>
      <div v-else class="text-gray-400 text-sm leading-relaxed">
        <slot></slot>
      </div>

      <!-- Link -->
      <div v-if="link" class="mt-6 flex items-center justify-center">
        <span class="text-[var(--electric-blue)] text-sm font-medium flex items-center gap-2 hover:gap-3 transition-all">
          Learn More
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M5 12h14M12 5l7 7-7 7"></path>
          </svg>
        </span>
      </div>
    </div>

    <!-- Corner Accents -->
    <div class="absolute top-0 left-0 w-10 h-10 border-t-2 border-l-2 border-[var(--electric-blue)]/30"></div>
    <div class="absolute top-0 right-0 w-10 h-10 border-t-2 border-r-2 border-[var(--electric-blue)]/30"></div>
    <div class="absolute bottom-0 left-0 w-10 h-10 border-b-2 border-l-2 border-[var(--electric-blue)]/30"></div>
    <div class="absolute bottom-0 right-0 w-10 h-10 border-b-2 border-r-2 border-[var(--electric-blue)]/30"></div>
  </component>
</template>

<script>
import { computed } from 'vue';

export default {
  name: '{$name}',
  props: {
    title: {
      type: String,
      default: '{$name}',
    },
    subtitle: {
      type: String,
      default: null,
    },
    content: {
      type: String,
      default: null,
    },
    image: {
      type: String,
      default: null,
    },
    icon: {
      type: [Object, Function],
      default: null,
    },
    variant: {
      type: String,
      default: 'default',
      validator: (value) => ['default', 'primary', 'danger', 'success', 'warning', 'dark', 'glass'].includes(value),
    },
    hover: {
      type: Boolean,
      default: true,
    },
    shadow: {
      type: Boolean,
      default: true,
    },
    rounded: {
      type: Boolean,
      default: true,
    },
    link: {
      type: String,
      default: null,
    },
    target: {
      type: String,
      default: '_self',
    },
  },
  setup(props) {
    const variantClasses = {
      default: 'bg-white/5 backdrop-blur-xl border border-white/10 hover:border-white/20',
      primary: 'bg-gradient-to-br from-[var(--electric-blue)]/20 to-[var(--indigo)]/20 border border-[var(--electric-blue)]/20 hover:border-[var(--electric-blue)]/40',
      danger: 'bg-gradient-to-br from-red-500/20 to-red-600/20 border border-red-500/20 hover:border-red-400/40',
      success: 'bg-gradient-to-br from-green-500/20 to-emerald-600/20 border border-green-500/20 hover:border-green-400/40',
      warning: 'bg-gradient-to-br from-yellow-500/20 to-orange-600/20 border border-yellow-500/20 hover:border-yellow-400/40',
      dark: 'bg-[#1a1a2e] border border-white/5 hover:border-white/10',
      glass: 'bg-white/10 backdrop-blur-2xl border border-white/20 hover:border-white/30',
    };

    const shadowClasses = computed(() => (props.shadow ? 'shadow-2xl shadow-black/50' : ''));
    const roundedClasses = computed(() => (props.rounded ? 'rounded-2xl' : 'rounded-none'));
    const hoverClasses = computed(() =>
      props.hover
        ? 'hover:scale-[1.02] hover:shadow-3xl hover:shadow-[var(--electric-blue)]/20 transform transition-all duration-500 ease-out'
        : ''
    );

    const classNames = computed(
      () =>
        `grid-card relative overflow-hidden ${
          variantClasses[props.variant]
        } ${shadowClasses.value} ${roundedClasses.value} ${hoverClasses.value}`
    );

    return {
      classNames,
    };
  },
};
</script>
VUE;
    }

    /**
     * Generate stub for Grid CN component
     */
    protected function generateGridCNStub($component, $name, $slug)
    {
        $type = $component['type'] ?? 'card';
        
        return <<< 'STUB'
<div class="grid-card grid-card-{$slug}" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
    <div class="grid-card-bg">
        <div class="grid-card-glow" :class="{ 'active': hover }"></div>
    </div>
    <div class="grid-card-content">
        <h3 class="grid-card-title">{{ $name }}</h3>
        <p class="grid-card-desc">{{ $type }} component from The Grid CN</p>
    </div>
</div>
STUB;
    }

    /**
     * Generate documentation for Grid CN component
     */
    protected function generateGridCNDoc($component, $name, $slug)
    {
        $type = $component['type'] ?? 'card}