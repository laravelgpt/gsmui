
<?php

namespace App\Components\Contracts;

interface HasActionsInterface
{
    /**
     * Get available actions
     */
    public function getActions(): array;

    /**
     * Execute action
     */
    public function executeAction(string $action, array $params = []): mixed;

    /**
     * Check if action is allowed
     */
    public function canExecute(string $action): bool;
}
