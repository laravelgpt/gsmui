<?php

namespace App\Components\Contracts;

interface RenderableInterface
{
    /**
     * Render component for specific stack
     */
    public function render(string $stack = 'blade'): string;

    /**
     * Get renderable data
     */
    public function toArray(): array;

    /**
     * Get props for stack
     */
    public function getProps(string $stack): array;
}
