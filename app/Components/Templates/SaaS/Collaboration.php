<?php

use function Livewire\Volt\set;

set('title', 'Collaboration');
set('subtitle', 'Team collaboration workspace');
set('features', []);

?>

<div>
    <!-- Hero Section -->
    <section class="hero-section bg-[var(--deep-space)] py-20">
        <div class="container mx-auto px-4">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-7xl font-bold mb-6 glow-blue">
                    {{ $title }}
                </h1>
                <p class="text-xl md:text-2xl text-gray-400 mb-8">
                    {{ $subtitle }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <x-components.volt.gsm-button 
                        label="Get Started" 
                        variant="primary" 
                        size="lg"
                        class="min-w-[160px]"
                    />
                    <x-components.volt.gsm-button 
                        label="Learn More" 
                        variant="ghost" 
                        size="lg"
                        class="min-w-[160px]"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section py-20 bg-[rgba(19,24,40,0.5)]">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 glow-green">
                Powerful Features
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $feature)
                    <div class="feature-card glass-card p-6 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-[rgba(0,212,255,0.15)] flex items-center justify-center">
                            <svg class="w-8 h-8 text-[#00D4FF]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                                <polyline points="2 17 12 22 22 17"></polyline>
                                <polyline points="2 12 12 17 22 12"></polyline>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-400">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-20">
        <div class="container mx-auto px-4">
            <div class="glass-card p-12 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Get Started?</h2>
                <p class="text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                    Join thousands of satisfied users today.
                </p>
                <x-components.volt.gsm-button 
                    label="Start Free Trial" 
                    variant="primary" 
                    size="lg"
                    class="min-w-[200px]"
                />
            </div>
        </div>
    </section>
</div>
