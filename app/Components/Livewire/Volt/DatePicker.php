<?php

use function Livewire\Volt\set;

set('title', null);
set('content', null);
set('variant', 'base');
set('size', 'md');
set('theme', 'default');
set('disabled', false);

?>

<div class="Forms-DatePicker theme-{{ $theme }} size-{{ $size }} variant-{{ $variant }}{{ $disabled ? ' opacity-50' : '' }}">
    @if($title)
        <h3 wire:text="title"></h3>
    @endif
    @if($content)
        <div wire:text="content"></div>
    @endif
</div>


/* Reduced Motion Support */
@media (prefers-reduced-motion: reduce) {
    .gsm-button {
    /* Midnight Electric Theme Integration */
    --electric-blue: #00D4FF;
    --toxic-green: #39FF14;
    --indigo: #6366F1;
        transition: none;
        animation: none;
    }
}
