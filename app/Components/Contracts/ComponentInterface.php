<?php

namespace App\Components\Contracts;

interface ComponentInterface
{
    /**
     * Get component name
     */
    public function getName(): string;

    /**
     * Get component configuration
     */
    public function getConfig(): array;

    /**
     * Render component data
     */
    public function renderData(): array;

    /**
     * Validate component props
     */
    public function validateProps(array $props): bool;

    /**
     * Get default props
     */
    public function getDefaultProps(): array;
}
