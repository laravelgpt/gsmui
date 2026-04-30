<?php

namespace App\Services;

use App\Models\User;
use App\Models\Component;
use App\Models\Template;
use App\Models\Purchase;

class ComponentAccessService
{
    /**
     * Check if user can access component
     */
    public function canAccessComponent(User $user, Component $component): bool
    {
        if ($component->type === 'free') {
            return true;
        }

        if ($this->hasActiveSubscription($user)) {
            return true;
        }

        return $this->hasPurchasedItem($user, 'component', $component->id);
    }

    /**
     * Check if user can access template
     */
    public function canAccessTemplate(User $user, Template $template): bool
    {
        if ($template->type === 'free') {
            return true;
        }

        if ($this->hasActiveSubscription($user)) {
            return true;
        }

        return $this->hasPurchasedItem($user, 'template', $template->id);
    }

    /**
     * Get component code if accessible
     */
    public function getComponentCode(User $user, Component $component): ?string
    {
        return $this->canAccessComponent($user, $component) 
            ? $component->code_snippet 
            : null;
    }

    /**
     * Get template HTML if accessible
     */
    public function getTemplateHTML(User $user, Template $template): ?string
    {
        return $this->canAccessTemplate($user, $template)
            ? $template->preview_html
            : null;
    }

    /**
     * Download component for CLI
     */
    public function downloadComponentForCLI(User $user, Component $component): array
    {
        if (!$this->canAccessComponent($user, $component)) {
            return [
                'success' => false,
                'error' => 'Access denied. Premium component requires subscription or purchase.',
                'code' => 403
            ];
        }

        $category = strtolower($component->category);
        $filename = $component->slug . '.blade.php';

        return [
            'success' => true,
            'code' => $component->code_snippet,
            'filename' => $filename,
            'path' => "resources/views/components/gsm/{$category}/{$filename}",
            'category' => $category
        ];
    }

    /**
     * Check active subscription
     */
    public function hasActiveSubscription(User $user): bool
    {
        return $user->has_active_subscription;
    }

    /**
     * Check if user purchased item
     */
    protected function hasPurchasedItem(User $user, string $type, int $id): bool
    {
        $modelClass = $type === 'component' 
            ? 'App\\Models\\Component' 
            : 'App\\Models\\Template';

        return Purchase::where('user_id', $user->id)
            ->where('purchasable_type', $modelClass)
            ->where('purchasable_id', $id)
            ->where('payment_status', 'completed')
            ->exists();
    }

    /**
     * Get accessible component IDs
     */
    public function getAccessibleComponentIds(User $user): array
    {
        $ids = [];

        // Free components
        $freeIds = Component::active()
            ->free()
            ->pluck('id')
            ->toArray();
        $ids = array_merge($ids, $freeIds);

        if ($this->hasActiveSubscription($user)) {
            $premiumIds = Component::active()
                ->premium()
                ->pluck('id')
                ->toArray();
            $ids = array_merge($ids, $premiumIds);
        } else {
            $purchasedIds = Purchase::where('user_id', $user->id)
                ->where('purchasable_type', 'App\\Models\\Component')
                ->where('payment_status', 'completed')
                ->pluck('purchasable_id')
                ->toArray();
            $ids = array_merge($ids, $purchasedIds);
        }

        return array_unique($ids);
    }
}

    /**
     * Record chat interaction
     */
    public function recordChatInteraction($chat)
    {
        // Track chat interactions for user analytics
        \App\Models\Analytics::recordInteraction('chat', [
            'user_id' => $chat->user_id,
            'message' => substr($chat->message, 0, 200),
            'category' => $chat->category,
            'type' => $chat->type,
        ]);
    }

    /**
     * Record download from chat context
     */
    public function recordChatDownload($user, $component)
    {
        \App\Models\Analytics::recordInteraction('chat_download', [
            'user_id' => $user->id,
            'component_id' => $component->id,
            'component_name' => $component->name,
        ]);
    }

    /**
     * Check if user can use chat features
     */
    public function canUseChatFeatures(User $user): bool
    {
        // All authenticated users can use basic chat
        // Premium features require active subscription
        return $this->hasActiveSubscription($user) || $user->role === 'admin';
    }
