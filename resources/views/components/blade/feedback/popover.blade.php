@props(['title' => null, 'content' => null, 'variant' => 'base', 'size' => 'md', 'theme' => 'default', 'disabled' => false])

<div class="feedback-popover theme-{{ $theme }} size-{{ $size }} variant-{{ $variant }}{{ $disabled ? ' opacity-50' : '' }}">
    @if($title)
        <div class="font-bold mb-2">{{ $title }}</div>
    @endif
    @if($content)
        <div class="content">{{ $content }}</div>
    @endif
    {{ $slot }}
</div>
