<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComponentChat;
use App\Services\ChatUIService;
use App\Services\ComponentAccessService;

class ChatUIController extends Controller
{
    protected $chatUIService;
    protected $componentAccessService;

    public function __construct(ChatUIService $chatUIService, ComponentAccessService $componentAccessService)
    {
        $this->chatUIService = $chatUIService;
        $this->componentAccessService = $componentAccessService;
    }

    /**
     * Display the chat UI landing page
     */
    public function index()
    {
        $suggestions = ComponentChat::where('is_active', true)
            ->where('category', 'suggestion')
            ->orderBy('sort_order')
            ->get();

        $recentInteractions = auth()->check() 
            ? ComponentChat::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
            : collect();

        return view('chatui.index', compact('suggestions', 'recentInteractions'));
    }

    /**
     * Handle chat message submission
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:2000',
            'context' => 'nullable|string|max:500',
            'attachment' => 'nullable|file|max:10240', // 10MB max
        ]);

        $userId = auth()->id();

        // Create chat entry
        $chat = ComponentChat::create([
            'user_id' => $userId,
            'message' => $validated['message'],
            'context' => $validated['context'] ?? null,
            'type' => 'user',
            'is_active' => true,
        ]);

        // Handle attachment if present
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('chat-attachments/' . ($userId ?? 'guest'), 'public');
            $chat->attachment_path = $path;
            $chat->save();
        }

        // Log access
        $this->componentAccessService->recordChatInteraction($chat);

        // Process through chat UI service
        $response = $this->chatUIService->processMessage($validated['message'], $userId);

        // Store AI response
        ComponentChat::create([
            'user_id' => $userId,
            'parent_id' => $chat->id,
            'message' => $response['content'],
            'context' => json_encode($response['context'] ?? []),
            'type' => 'assistant',
            'is_active' => true,
            'metadata' => json_encode($response['metadata'] ?? []),
        ]);

        return response()->json([
            'success' => true,
            'user_message' => $chat,
            'assistant_response' => $response,
        ]);
    }

    /**
     * Handle suggestion chip click
     */
    public function selectSuggestion(Request $request, $id)
    {
        $suggestion = ComponentChat::findOrFail($id);

        if ($suggestion->category !== 'suggestion') {
            return response()->json(['error' => 'Invalid suggestion'], 400);
        }

        return response()->json([
            'success' => true,
            'message' => $suggestion->message,
            'template' => $suggestion->template_data,
        ]);
    }

    /**
     * Toggle microphone recording state
     */
    public function toggleRecording(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:start,stop',
            'audio_data' => 'nullable|string',
        ]);

        if ($validated['action'] === 'start') {
            $recording = ComponentChat::create([
                'user_id' => auth()->id(),
                'message' => '',
                'type' => 'recording',
                'is_active' => true,
                'metadata' => json_encode(['status' => 'recording', 'started_at' => now()]),
            ]);

            return response()->json([
                'success' => true,
                'recording_id' => $recording->id,
                'status' => 'recording',
            ]);
        }

        // Stop recording
        if ($validated['action'] === 'stop' && isset($validated['audio_data'])) {
            $recording = ComponentChat::where('user_id', auth()->id())
                ->where('type', 'recording')
                ->where('metadata->status', 'recording')
                ->latest()
                ->first();

            if ($recording) {
                $recording->metadata = json_encode([
                    'status' => 'completed',
                    'duration' => now()->diffInSeconds($recording->created_at),
                    'audio_data' => $validated['audio_data'],
                ]);
                $recording->save();
            }

            return response()->json([
                'success' => true,
                'status' => 'stopped',
                'duration' => $recording ? $recording->created_at->diffInSeconds(now()) : 0,
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get chat history
     */
    public function getHistory(Request $request)
    {
        $userId = auth()->id();
        $limit = $request->get('limit', 50);

        $chats = ComponentChat::with('parent')
            ->where('user_id', $userId)
            ->where('type', '!=', 'recording')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->groupBy(function ($chat) {
                return $chat->created_at->format('Y-m-d');
            });

        return view('chatui.history', compact('chats'));
    }

    /**
     * Clear chat history
     */
    public function clearHistory(Request $request)
    {
        ComponentChat::where('user_id', auth()->id())
            ->delete();

        return redirect()->route('chatui.index')
            ->with('success', 'Chat history cleared successfully.');
    }

    /**
     * Handle web search action from toolbar
     */
    public function webSearch(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2|max=200',
        ]);

        // Integrate with search service
        $results = $this->chatUIService->performWebSearch($validated['query']);

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }

    /**
     * Upload image attachment
     */
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:5120', // 5MB
        ]);

        $path = $request->file('image')->store('chat-images/' . (auth()->id() ?? 'guest'), 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $path),
            'path' => $path,
        ]);
    }

    /**
     * Get available templates for chat context
     */
    public function getTemplates(Request $request)
    {
        $category = $request->get('category', 'all');

        $templates = ComponentChat::where('category', 'template')
            ->when($category !== 'all', function ($q) use ($category) {
                $q->where('template_category', $category);
            })
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'templates' => $templates,
        ]);
    }

    /**
     * Get color palette options
     */
    public function getColorPalettes(Request $request)
    {
        $palettes = $this->chatUIService->getColorPalettes();

        return response()->json([
            'success' => true,
            'palettes' => $palettes,
        ]);
    }
}
