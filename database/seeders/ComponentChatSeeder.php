<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComponentChat;

class ComponentChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Suggestion chips for chat UI
        $suggestions = [
            [
                'message' => '✨ Generate an image',
                'category' => 'suggestion',
                'type' => 'suggestion',
                'template_category' => 'image-generation',
                'template_data' => json_encode([
                    'prompt_prefix' => 'Create an image of',
                    'action' => 'generate_image',
                    'icon' => 'image',
                ]),
                'sort_order' => 1,
            ],
            [
                'message' => '📝 Write a story',
                'category' => 'suggestion',
                'type' => 'suggestion',
                'template_category' => 'creative-writing',
                'template_data' => json_encode([
                    'prompt_prefix' => 'Write a story about',
                    'action' => 'write_story',
                    'icon' => 'book',
                ]),
                'sort_order' => 2,
            ],
            [
                'message' => '💡 Brainstorm ideas',
                'category' => 'suggestion',
                'type' => 'suggestion',
                'template_category' => 'brainstorming',
                'template_data' => json_encode([
                    'prompt_prefix' => 'Brainstorm ideas for',
                    'action' => 'brainstorm',
                    'icon' => 'lightbulb',
                ]),
                'sort_order' => 3,
            ],
            [
                'message' => '🔍 Research a topic',
                'category' => 'suggestion',
                'type' => 'suggestion',
                'template_category' => 'research',
                'template_data' => json_encode([
                    'prompt_prefix' => 'Research and summarize',
                    'action' => 'research',
                    'icon' => 'search',
                ]),
                'sort_order' => 4,
            ],
        ];

        foreach ($suggestions as $suggestion) {
            ComponentChat::updateOrCreate(
                ['message' => $suggestion['message'], 'category' => 'suggestion'],
                $suggestion
            );
        }

        // Sample conversations for demo
        $sampleConversation = [
            [
                'user_id' => null,
                'parent_id' => null,
                'message' => 'What is artificial intelligence?',
                'context' => null,
                'type' => 'user',
                'category' => 'general',
                'template_category' => null,
                'template_data' => null,
                'attachment_path' => null,
                'metadata' => json_encode(['source' => 'demo']),
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'user_id' => null,
                'parent_id' => 1,
                'message' => 'Artificial Intelligence (AI) refers to the simulation of human intelligence in machines that are programmed to think and learn like humans. This includes capabilities like reasoning, learning, problem-solving, perception, and language understanding.',
                'context' => null,
                'type' => 'assistant',
                'category' => 'general',
                'template_category' => null,
                'template_data' => null,
                'attachment_path' => null,
                'metadata' => json_encode(['source' => 'demo', 'confidence' => 0.95]),
                'is_active' => true,
                'sort_order' => 0,
            ],
        ];

        foreach ($sampleConversation as $chat) {
            ComponentChat::updateOrCreate(
                ['message' => $chat['message'], 'type' => $chat['type'], 'category' => $chat['category']],
                $chat
            );
        }
    }
}
