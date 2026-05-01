<div class="min-h-screen bg-[#0a1628] text-gray-100">
    <!-- Header -->
    <div class="bg-[#111e30] border-b border-[rgba(0,212,170,0.12)] p-4">
        <div class="flex items-center justify-between max-w-6xl mx-auto">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-[#00d4aa] to-[#00a8e8] flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-semibold">AI Chat Interface</h1>
                    <p class="text-xs text-gray-400">Real-time conversation</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span wire:loading wire:target="sendMessage" class="text-sm text-[#00d4aa]">Sending...</span>
                <button wire:click="clearChat" class="text-xs text-gray-400 hover:text-red-400 transition">
                    Clear Chat
                </button>
            </div>
        </div>
    </div>

    <!-- Chat Messages -->
    <div class="max-w-6xl mx-auto p-4 h-[calc(100vh-200px)] overflow-y-auto space-y-4" wire:scroll-load>
        @forelse($chats as $chat)
        <div class="flex {{ $chat->type === 'user' ? 'justify-end' : 'justify-start' }}">
            <div class="max-w-[80%] lg:max-w-md">
                @if($chat->type === 'user')
                <div class="bg-gradient-to-r from-[#00d4aa] to-[#00a8e8] rounded-2xl rounded-br-md px-4 py-2 shadow-lg">
                    <p class="text-sm text-white">{{ $chat->message }}</p>
                    @if($chat->attachment_path)
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $chat->attachment_path) }}" target="_blank" 
                           class="text-xs text-white/80 hover:text-white underline">
                            📎 Attachment
                        </a>
                    </div>
                    @endif
                    <span class="text-xs text-white/40 mt-1 block">{{ $chat->created_at->format('H:i') }}</span>
                </div>
                @else
                <div class="bg-[#1a2d42] rounded-2xl rounded-bl-md px-4 py-2 border border-[rgba(255,255,255,0.05)]">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="w-2 h-2 rounded-full bg-[#00d4aa]"></span>
                        <span class="text-xs text-gray-400">AI Assistant</span>
                    </div>
                    <p class="text-sm text-gray-200 whitespace-pre-wrap">{{ $chat->message }}</p>
                    <span class="text-xs text-gray-500 mt-1 block">{{ $chat->created_at->format('H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center h-full text-center">
            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#00d4aa]/20 to-[#00a8e8]/20 flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-[#00d4aa]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-300 mb-2">No messages yet</h3>
            <p class="text-gray-500 mb-6">Start a conversation with the AI assistant</p>
            @if(count($suggestions) > 0)
            <div class="w-full max-w-md">
                <p class="text-sm text-gray-400 mb-3">Try these suggestions:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($suggestions as $suggestion)
                    <button wire:click="selectSuggestion({{ $suggestion->id }})"
                            class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 text-gray-300 text-sm hover:bg-[#00d4aa]/20 hover:border-[#00d4aa] transition">
                        {{ $suggestion->message }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Input Area -->
    <div class="max-w-6xl mx-auto px-4 py-4 bg-[#111e30] border-t border-[rgba(0,212,170,0.12)]">
        @if($selectedSuggestion)
        <div class="mb-3 p-3 bg-[#00d4aa]/10 border border-[#00d4aa]/20 rounded-lg flex items-center justify-between">
            <span class="text-sm text-[#00d4aa]">Using suggestion as base</span>
            <button wire:click="selectedSuggestion = null" class="text-gray-400 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        @endif

        <div class="flex gap-3 items-end">
            <!-- Attachment -->
            <label class="p-3 rounded-xl bg-[#1a2d42] border border-[rgba(255,255,255,0.05)] hover:border-[#00d4aa]/30 cursor-pointer transition" title="Attach file">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                </svg>
                <input type="file" wire:model="attachment" class="hidden">
            </label>

            <!-- Message Input -->
            <div class="flex-1 relative">
                <textarea wire:model.debounce.500ms="message" 
                          @keydown.enter.prevent="sendMessage"
                          placeholder="Type your message... (Enter to send, Shift+Enter for newline)"
                          class="w-full px-4 py-3 rounded-xl bg-[#0a0f12] border border-[rgba(255,255,255,0.05)] text-white placeholder-gray-500 focus:outline-none focus:border-[#00d4aa] resize-none min-h-[50px] max-h-[150px] pr-12"></textarea>
                @error('message') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                
                @if($attachment)
                <div class="absolute bottom-full left-0 mb-2">
                    <div class="flex items-center gap-2 bg-[#1a2d42] px-3 py-1.5 rounded-lg text-sm">
                        <span class="text-gray-400">📎 {{ $attachment->getClientOriginalName() }}</span>
                        <button wire:click="attachment = null" class="text-gray-400 hover:text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            <!-- Mic Button -->
            <button wire:click="toggleRecording" 
                    class="p-3 rounded-xl transition-all {{ $isRecording ? 'bg-red-500/30 border-red-500' : 'bg-[#1a2d42] border border-[rgba(255,255,255,0.05)] hover:border-[#00d4aa]/30' }}" 
                    title="Voice input">
                <svg class="w-5 h-5 {{ $isRecording ? 'text-red-400 animate-pulse' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                    <path d="M19 10v2a7 7 0 0 1-14 0v-2M12 19v4M8 23h8"/>
                </svg>
            </button>

            <!-- Send Button -->
            <button wire:click="sendMessage" wire:loading.attr="disabled"
                    class="p-3 rounded-xl bg-gradient-to-r from-[#00d4aa] to-[#00a8e8] hover:opacity-90 transition-all hover:scale-105 disabled:opacity-50 disabled:scale-100"
                    title="Send message">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </button>
        </div>

        <!-- Suggestions -->
        @if(count($suggestions) > 0 && empty($selectedSuggestion))
        <div class="mt-4">
            <p class="text-xs text-gray-500 mb-2">Quick suggestions:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($suggestions as $suggestion)
                <button wire:click="selectSuggestion({{ $suggestion->id }})"
                        class="px-3 py-1.5 rounded-xl bg-[#1a2d42] border border-[rgba(255,255,255,0.05)] text-gray-400 text-sm hover:bg-[#00d4aa]/20 hover:text-[#00d4aa] hover:border-[#00d4aa]/30 transition">
                    {{ $suggestion->message }}
                </button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll to bottom
    document.addEventListener('livewire:load', () => {
        const chatContainer = document.querySelector('.overflow-y-auto');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }
    });

    // Listen for scroll-to-bottom event
    Livewire.on('scroll-to-bottom', () => {
        setTimeout(() => {
            const chatContainer = document.querySelector('.overflow-y-auto');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }, 100);
    });

    // Recording animation
    Livewire.on('recordingToggled', (isRecording) => {
        const micBtn = document.querySelector('[wire\\:click="toggleRecording"]');
        if (isRecording) {
            micBtn.classList.add('animate-pulse');
        } else {
            micBtn.classList.remove('animate-pulse');
        }
    });

    // Auto-resize textarea
    document.addEventListener('input', (e) => {
        if (e.target.tagName === 'TEXTAREA') {
            e.target.style.height = 'auto';
            e.target.style.height = Math.min(e.target.scrollHeight, 150) + 'px';
        }
    });
</script>
@endpush
