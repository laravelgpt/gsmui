<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ChatUIService
{
    /**
     * Process a chat message and return AI response
     */
    public function processMessage(string $message, ?int $userId = null): array
    {
        $context = $this->buildContext($userId);

        // Simulate AI response generation (in production, integrate with OpenAI/Anthropic/etc.)
        $response = $this->generateResponse($message, $context);

        // Cache the interaction for rate limiting
        $cacheKey = 'chat_user_' . ($userId ?? 'guest') . '_' . now()->timestamp;
        Cache::put($cacheKey, $message, 300); // 5 minute cache

        Log::info('Chat message processed', [
            'user_id' => $userId,
            'message_length' => strlen($message),
            'response_length' => strlen($response['content']),
        ]);

        return $response;
    }

    /**
     * Build context from user history
     */
    protected function buildContext(?int $userId): array
    {
        $context = [
            'user_id' => $userId,
            'timestamp' => now()->toIso8601String(),
            'session_id' => session()->getId(),
        ];

        if ($userId) {
            // Add recent interaction count
            $context['recent_interactions'] = \App\Models\ComponentChat::where('user_id', $userId)
                ->where('created_at', '>=', now()->subHours(24))
                ->count();
        }

        return $context;
    }

    /**
     * Generate AI response based on message
     */
    protected function generateResponse(string $message, array $context): array
    {
        // In production, this would call an actual AI API
        // For now, generate context-aware placeholder responses

        $messageLower = strtolower($message);

        $responses = [
            'image' => 'I can help you generate images! Please describe what you\'d like to create, and I can generate an image for you.',
            'story' => 'I\'d love to write a story! Give me a topic, character, or setting to start with.',
            'idea' => 'Let\'s brainstorm! What topic or problem would you like to explore?',
            'research' => 'I can help with research! What topic would you like me to look into?',
            'hello' => 'Hello! I\'m here to help. You can ask me anything or try one of the suggestions above.',
            'hi' => 'Hi there! How can I help you today?',
            'help' => 'I can assist with generating images, writing stories, brainstorming ideas, and researching topics. Try one of the suggestions or ask me anything!',
            'thank' => 'You\'re welcome! Is there anything else I can help you with?',
        ];

        foreach ($responses as $keyword => $response) {
            if (strpos($messageLower, $keyword) !== false) {
                return [
                    'content' => $response,
                    'context' => $context,
                    'metadata' => [
                        'matched_keyword' => $keyword,
                        'type' => 'keyword_response',
                    ],
                ];
            }
        }

        // Default response
        return [
            'content' => 'Thank you for your message: "' . substr($message, 0, 100) . '". I understand you\'re interested in this topic. Try one of the quick suggestions ("Generate an image", "Write a story", etc.) or tell me more about what you\'d like to explore! I can help with creative writing, brainstorming, research, and image generation.',
            'context' => $context,
            'metadata' => [
                'type' => 'default_response',
                'message_category' => 'general',
            ],
        ];
    }

    /**
     * Perform web search
     */
    public function performWebSearch(string $query): array
    {
        // In production, integrate with search API (SerpAPI, Google Search, etc.)
        // For demo, return mock results

        Log::info('Web search performed', ['query' => $query]);

        return [
            [
                'title' => $query . ' - Wikipedia',
                'url' => 'https://en.wikipedia.org/wiki/' . urlencode(str_replace(' ', '_', $query)),
                'snippet' => 'Learn more about ' . $query . ' from this comprehensive resource.',
                'source' => 'web',
            ],
            [
                'title' => 'Latest news about ' . $query,
                'url' => '#',
                'snippet' => 'Recent developments and news articles related to ' . $query . '.',
                'source' => 'news',
            ],
            [
                'title' => $query . ' tutorials and guides',
                'url' => '#',
                'snippet' => 'Step-by-step tutorials to help you learn and master ' . $query . '.',
                'source' => 'tutorials',
            ],
        ];
    }

    /**
     * Get color palette options
     */
    public function getColorPalettes(): array
    {
        return [
            [
                'name' => 'Midnight Electric',
                'colors' => ['#0B0F19', '#111E30', '#00D4AA', '#39FF14', '#6366F1'],
                'primary' => '#00D4AA',
            ],
            [
                'name' => 'Solar Flare',
                'colors' => ['#1A0B2E', '#2D1B4E', '#FF6B35', '#F7931E', '#FFD23F'],
                'primary' => '#FF6B35',
            ],
            [
                'name' => 'Ocean Depth',
                'colors' => ['#0C1445', '#1A237E', '#00BCD4', '#26C6DA', '#80DEEA'],
                'primary' => '#00BCD4',
            ],
            [
                'name' => 'Forest Night',
                'colors' => ['#0A1A0A', '#1B3A1B', '#2E7D32', '#4CAF50', '#81C784'],
                'primary' => '#4CAF50',
            ],
            [
                'name' => 'Rose Quartz',
                'colors' => ['#2D1B3D', '#4A235A', '#E91E63', '#F06292', '#F8BBD0'],
                'primary' => '#E91E63',
            ],
        ];
    }

    /**
     * Get available templates by category
     */
    public function getTemplatesByCategory(string $category = 'all'): array
    {
        $categories = [
            'image-generation' => [
                'AI Portrait Generator',
                'Landscape Creator',
                'Product Mockup',
                'Logo Designer',
            ],
            'creative-writing' => [
                'Short Story Generator',
                'Poetry Assistant',
                'Blog Post Writer',
                'Script Creator',
            ],
            'brainstorming' => [
                'Idea Generator',
                'Problem Solver',
                'Strategy Planner',
                'Concept Developer',
            ],
            'research' => [
                'Topic Researcher',
                'Fact Checker',
                'Summary Generator',
                'Analyst Assistant',
            ],
        ];

        if ($category === 'all') {
            return $categories;
        }

        return $categories[$category] ?? [];
    }

    /**
     * Record chat interaction for analytics
     */
    public function recordChatInteraction($chat)
    {
        // Record metrics for analytics
        \App\Models\Analytics::recordInteraction('chat_message', [
            'user_id' => $chat->user_id,
            'message_length' => strlen($chat->message),
            'category' => $chat->category,
            'type' => $chat->type,
        ]);
    }
}
