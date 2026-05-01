<?php

namespace App\Http\Livewire\Chat;

use Livewire\Component;
use App\Models\ComponentChat;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ChatInterface extends Component
{
    use WithFileUploads, WithPagination;

    public $message = '';
    public $attachment;
    public $isRecording = false;
    public $showSuggestions = true;
    public $selectedSuggestion;

    protected $rules = [
        'message' => 'nullable|string|max:2000',
        'attachment' => 'nullable|file|max:10240',
    ];

    protected $listeners = [
        'messageSent',
        'suggestionSelected',
        'startRecording',
        'stopRecording',
        'webSearch',
    ];

    public function mount()
    {
        $this->loadSuggestions();
    }

    public function render()
    {
        $chats = ComponentChat::where(function ($query) {
            $query->where('user_id', Auth::id())
                  ->orWhereNull('user_id');
        })
        ->with('parent')
        ->orderBy('created_at', 'desc')
        ->paginate(50);

        $suggestions = ComponentChat::where('category', 'suggestion')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.chat.chat-interface', compact('chats', 'suggestions'))
            ->layout('layouts.app');
    }

    public function sendMessage()
    {
        if (empty($this->message) && empty($this->attachment)) {
            return;
        }

        $this->validate();

        $chat = ComponentChat::create([
            'user_id' => Auth::id(),
            'message' => $this->message,
            'type' => 'user',
            'category' => 'general',
            'is_active' => true,
        ]);

        if ($this->attachment) {
            $path = $this->attachment->store('chat-attachments/' . Auth::id(), 'public');
            $chat->update(['attachment_path' => $path]);
        }

        $this->emit('messageSent', $chat);
        $this->dispatchBrowserEvent('scroll-to-bottom');

        // Clear input
        $this->reset(['message', 'attachment']);

        // Simulate AI response (in production, this would be async)
        $this->sendAIResponse($chat);
    }

    public function sendAIResponse($userChat)
    {
        $responses = [
            "I understand your query. Let me help you with that!",
            "Great question! Here's what I can do for you...",
            "Thanks for reaching out. Based on what you've shared, I suggest...",
            "That's an interesting point! Let me provide more information...",
            "I can definitely help with that. Here's my recommendation...",
        ];

        $response = $responses[array_rand($responses)];

        ComponentChat::create([
            'user_id' => Auth::id(),
            'parent_id' => $userChat->id,
            'message' => $response,
            'type' => 'assistant',
            'category' => 'general',
            'is_active' => true,
        ]);

        $this->dispatchBrowserEvent('scroll-to-bottom');
    }

    public function selectSuggestion($id)
    {
        $suggestion = ComponentChat::find($id);

        if ($suggestion) {
            $this->message = $suggestion->message;
            $this->selectedSuggestion = $id;
            $this->dispatchBrowserEvent('suggestion-selected', $id);
        }
    }

    public function toggleRecording()
    {
        $this->isRecording = !$this->isRecording;
        $this->emit('recordingToggled', $this->isRecording);
    }

    public function performSearch($query)
    {
        $this->emit('webSearch', $query);
    }

    public function messageSent($chat)
    {
        // Event handler for when message is sent
        $this->dispatchBrowserEvent('message-sent', $chat);
    }

    public function suggestionSelected($id)
    {
        $this->selectSuggestion($id);
    }

    public function clearChat()
    {
        if (Auth::check()) {
            ComponentChat::where('user_id', Auth::id())->delete();
            $this->emit('chatCleared');
            $this->dispatchBrowserEvent('refresh-chat');
        }
    }

    public function loadMore()
    {
        $this->emit('load-more');
    }
}
ENDOFFILE
echo "ChatInterface Livewire created"
