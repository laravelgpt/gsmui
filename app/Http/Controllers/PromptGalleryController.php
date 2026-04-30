<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PromptGalleryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');
        $search = $request->get('search', '');
        $framework = $request->get('framework', 'all');

        $prompts = $this->getPrompts($category, $search, $framework);
        $categories = $this->getCategories();
        $frameworks = $this->getFrameworks();
        $featuredPrompts = $this->getFeaturedPrompts();

        return view('prompt-gallery.index', compact(
            'prompts', 'categories', 'frameworks', 'featuredPrompts',
            'category', 'search', 'framework'
        ));
    }

    public function show($id)
    {
        $prompt = $this->findPrompt($id);
        if (!$prompt) abort(404);
        $relatedPrompts = $this->getRelatedPrompts($prompt);
        return view('prompt-gallery.show', compact('prompt', 'relatedPrompts'));
    }

    public function copy(Request $request, $id)
    {
        $prompt = $this->findPrompt($id);
        if (!$prompt) return response()->json(['error' => 'Not found'], 404);
        $this->trackEvent('prompt_copied', ['prompt_id' => $id, 'user_id' => auth()->id()]);
        return response()->json(['success' => true, 'prompt' => $prompt['content'], 'message' => 'Copied!']);
    }

    protected function getPrompts($category, $search, $framework)
    {
        $all = $this->getAllPrompts();
        return collect($all)
            ->when($category !== 'all', fn($c) => $c->where('category', $category))
            ->when($framework !== 'all', fn($c) => $c->where('framework', $framework))
            ->when($search, fn($c) => $c->filter(fn($p) => stripos($p['title'], $search) !== false || stripos($p['description'], $search) !== false))
            ->sortByDesc('featured')
            ->values()->all();
    }

    protected function findPrompt($id)
    {
        foreach ($this->getAllPrompts() as $prompt) {
            if ($prompt['id'] == $id) return $prompt;
        }
        return null;
    }

    protected function getRelatedPrompts($prompt)
    {
        return collect($this->getAllPrompts())
            ->where('category', $prompt['category'])
            ->where('id', '!=', $prompt['id'])
            ->take(3)->all();
    }

    protected function getCategories() { return ['dashboard', 'auth', 'button', 'card', 'form']; }
    protected function getFrameworks() { return ['react', 'vue', 'svelte', 'angular']; }
    protected function getFeaturedPrompts() { return collect($this->getAllPrompts())->where('featured', true)->take(3)->all(); }

    protected function getAllPrompts()
    {
        return [
            [
                'id' => 1,
                'title' => 'React Modern Dashboard',
                'description' => 'Sleek dashboard with React, charts, and Tailwind CSS.',
                'category' => 'dashboard',
                'framework' => 'react',
                'content' => "// React Dashboard\nimport React from 'react';\nconst Dashboard = () => {\n  return (\n    <div className=\"p-6\">\n      <h1 className=\"text-3xl font-bold\">Dashboard</h1>\n    </div>\n  );\n};\nexport default Dashboard;",
                'language' => 'jsx',
                'tags' => ['react', 'dashboard', 'tailwind'],
                'featured' => true,
                'author' => 'VibeUI Team',
                'complexity' => 'intermediate',
                'created_at' => '2026-04-28',
            ],
            [
                'id' => 2,
                'title' => 'Glassmorphism Auth Form',
                'description' => 'Glassmorphism login with floating particles in Vue.',
                'category' => 'auth',
                'framework' => 'vue',
                'content' => "<template><div class=\\\"min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-900 to-indigo-900\\\">\n  <div class=\\\"bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 p-8\\\">\n    <h1 class=\\\"text-3xl font-bold text-white\\\">Welcome Back</h1>\n    <form class=\\\"mt-6 space-y-4\\\"><input type=\\\"email\\\" placeholder=\\\"Email\\\" class=\\\"w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white\\\" />\n    <input type=\\\"password\\\" placeholder=\\\"Password\\\" class=\\\"w-full px-4 py-3 rounded-xl bg-white/10 border border-white/20 text-white\\\" />\n    <button class=\\\"w-full py-3 bg-white text-purple-900 font-semibold rounded-xl\\\">Sign In</button></form>\n  </div>\n</div></template>",
                'language' => 'vue',
                'tags' => ['vue', 'glassmorphism', 'auth'],
                'featured' => true,
                'author' => 'GlowUpUI',
                'complexity' => 'intermediate',
                'created_at' => '2026-04-27',
            ],
            [
                'id' => 3,
                'title' => 'Cyberpunk Button Effects',
                'description' => 'Neon cyberpunk button with glow effects in React.',
                'category' => 'button',
                'framework' => 'react',
                'content' => "import React from 'react';\nconst CyberButton = ({ children }) => {\n  return (\n    <button className=\\\"cyber-button\\\">\n      <span>{children}</span>\n    </button>\n  );\n};\nexport default CyberButton;",
                'language' => 'jsx',
                'tags' => ['react', 'animation', 'cyberpunk'],
                'featured' => false,
                'author' => 'NeonDev',
                'complexity' => 'advanced',
                'created_at' => '2026-04-26',
            ],
            [
                'id' => 4,
                'title' => 'Glassmorphism Card Grid',
                'description' => 'Responsive glass cards with hover effects in Vue.',
                'category' => 'card',
                'framework' => 'vue',
                'content' => "<template><div class=\\\"min-h-screen bg-[#0a0f1a] p-8\\\">\n  <div class=\\\"grid grid-cols-3 gap-8\\\">\n    <div class=\\\"bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6\\\">\n      <h3 class=\\\"text-white\\\">Card Title</h3>\n    </div>\n  </div>\n</div></template>",
                'language' => 'vue',
                'tags' => ['vue', 'glassmorphism', 'card'],
                'featured' => false,
                'author' => 'GlassUI',
                'complexity' => 'intermediate',
                'created_at' => '2026-04-25',
            ],
            [
                'id' => 5,
                'title' => 'Bento Grid Dashboard',
                'description' => 'Apple-style bento grid layout for dashboard widgets.',
                'category' => 'dashboard',
                'framework' => 'react',
                'content' => "import React from 'react';\nconst BentoGrid = () => {\n  return (\n    <div className=\\\"p-8\\\">\n      <h1 className=\\\"text-5xl font-bold\\\">Dashboard</h1>\n    </div>\n  );\n};\nexport default BentoGrid;",
                'language' => 'jsx',
                'tags' => ['react', 'dashboard', 'bento'],
                'featured' => false,
                'author' => 'AppleStyle',
                'complexity' => 'intermediate',
                'created_at' => '2026-04-24',
            ],
        ];
    }
}
