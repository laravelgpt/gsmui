@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0a1628] flex items-center justify-center p-4 overflow-hidden relative">
    <!-- Background gradient layers -->
    <div class="bg-gradient absolute inset-0 z-0"
         style="background:
            radial-gradient(ellipse 80% 60% at 50% 120%, rgba(0, 212, 170, 0.08) 0%, transparent 70%),
            radial-gradient(ellipse 60% 50% at 20% 20%, rgba(0, 80, 120, 0.15) 0%, transparent 60%),
            radial-gradient(ellipse 50% 40% at 80% 30%, rgba(0, 140, 160, 0.08) 0%, transparent 60%),
            linear-gradient(180deg, #060e1a 0%, #0a1628 40%, #0d1c30 100%);">
    </div>

    <!-- Background grid -->
    <div class="bg-grid absolute inset-0 z-0 opacity-30"
         style="background-image:
            linear-gradient(rgba(0, 212, 170, 0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(0, 212, 170, 0.02) 1px, transparent 1px);
            background-size: 60px 60px;">
    </div>

    <!-- Floating particles -->
    @foreach([15, 20, 25, 30, 35] as $index)
    @php
        $delays = ['0s', '2s', '4s', '1s', '3s'];
        $lefts = ['15%', '75%', '60%', '30%', '85%'];
        $tops = ['30%', '20%', '70%', '65%', '55%'];
        $sizes = ['3px', '2px', '4px', '2px', '3px'];
    @endphp
    <div class="particle absolute rounded-full bg-[#00d4aa] opacity-30 animate-float-particle"
         style="width: {{ $sizes[$index-15] }};
                height: {{ $sizes[$index-15] }};
                left: {{ $lefts[$index-15] }};
                top: {{ $tops[$index-15] }};
                animation-delay: {{ $delays[$index-15] }};">
    </div>
    @endforeach

    <!-- Chat container -->
    <div class="chat-container relative z-10 w-full max-w-[680px] px-6 animate-slide-up">
        <!-- Greeting -->
        <div class="greeting text-center mb-10 animate-fade-in" style="animation-delay: 0.3s;">
            <h1 class="text-[28px] font-semibold text-[#e8f0f8] leading-tight mb-2 tracking-[-0.5px]">
                What can I <span class="bg-gradient-to-r from-[#00d4aa] to-[#00a8e8] bg-clip-text text-transparent">help</span> you with?
            </h1>
            <p class="text-[15px] text-[#5a7a94] font-normal">
                Ask anything — I'm ready when you are.
            </p>
        </div>

        <!-- Chat box -->
        <div class="chat-box relative">
            <!-- Gradient border pseudo element -->
            <div class="absolute inset-0 rounded-[21px] opacity-0 transition-opacity duration-300 pointer-events-none"
                 style="background: linear-gradient(135deg, rgba(0, 212, 170, 0.2), transparent 50%, rgba(0, 168, 232, 0.1));
                        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
                        mask-composite: exclude;
                        -webkit-mask-composite: xor;">
            </div>

            <div class="relative bg-[#111e30] border border-[rgba(0,212,170,0.12)] rounded-[20px] transition-all duration-300 focus-within:border-[rgba(0,212,170,0.25)] focus-within:shadow-[0_0_0_4px_rgba(0,212,170,0.06),0_20px_60px_rgba(0,0,0,0.3)]" id="chat-box">
                
                <!-- Message display area -->
                <div id="message-area" class="max-h-[300px] overflow-y-auto mb-2">
                    <!-- Messages will appear here -->
                </div>

                <!-- Input area -->
                <div class="input-area px-5 pt-4 pb-3">
                    <textarea 
                        id="message-input"
                        class="w-full bg-transparent border-none outline-none resize-none text-[#e8f0f8] text-[16px] leading-[1.5] font-[inherit] min-h-[26px] max-h-[120px] placeholder-[#5a7a94]"
                        placeholder="Type your message here..."
                        rows="1"
                    ></textarea>
                </div>

                <!-- Toolbar -->
                <div class="toolbar flex items-center justify-between px-3 pb-3">
                    <!-- Left toolbar -->
                    <div class="toolbar-left flex items-center gap-[2px]">
                        <button class="tool-btn relative group" data-tooltip="Attach file" type="button" onclick="handleAttach()">
                            @include('chatui.icons.attach')
                        </button>
                        
                        <button class="tool-btn relative group" data-tooltip="Web search" type="button" onclick="handleWebSearch()">
                            @include('chatui.icons.search')
                        </button>

                        <div class="divider w-[1px] h-[18px] bg-[rgba(90,122,148,0.2)] mx-[6px]"></div>

                        <button class="tool-btn relative group" data-tooltip="Settings" type="button">
                            @include('chatui.icons.settings')
                        </button>

                        <button class="tool-btn relative group" data-tooltip="Image generation" type="button" onclick="handleImageGen()">
                            @include('chatui.icons.image')
                        </button>

                        <button class="tool-btn relative group" data-tooltip="Components" type="button" onclick="handleComponents()">
                            @include('chatui.icons.components')
                        </button>

                        <div class="divider w-[1px] h-[18px] bg-[rgba(90,122,148,0.2)] mx-[6px]"></div>

                        <button class="tool-btn relative group" data-tooltip="Templates" type="button" onclick="handleTemplates()">
                            @include('chatui.icons.template')
                        </button>

                        <button class="tool-btn relative group" data-tooltip="Color palette" type="button" onclick="handleColorPalette()">
                            @include('chatui.icons.color')
                        </button>
                    </div>

                    <!-- Right toolbar -->
                    <div class="toolbar-right flex items-center gap-2">
                        <button class="mic-btn relative flex items-center justify-center w-12 h-12 rounded-[14px] bg-[rgba(0,212,170,0.06)] border border-[rgba(0,212,170,0.15)] text-[#00d4aa] transition-all duration-200 hover:bg-[rgba(0,212,170,0.12)] hover:scale-[1.05] active:scale-[0.95] group" 
                                type="button"
                                id="mic-btn"
                                onclick="toggleRecording()"
                                data-tooltip="Voice input">
                            @include('chatui.icons.mic')
                            <span class="sr-only">Voice input</span>
                        </button>

                        <button class="send-btn relative flex items-center justify-center w-12 h-12 rounded-[14px] bg-[#00d4aa] text-[#0a1628] transition-all duration-250 ease-[cubic-bezier(0.22,1,0.36,1)] hover:scale-[1.08] hover:shadow-[0_6px_24px_rgba(0,212,170,0.35)] active:scale-[0.95] group"
                                type="button"
                                id="send-btn"
                                onclick="sendMessage()">
                            @include('chatui.icons.send')
                            <span class="sr-only">Send message</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suggestions -->
        <div class="suggestions flex flex-wrap justify-center gap-2 mt-4 animate-fade-in" style="animation-delay: 0.6s;">
            @foreach($suggestions as $suggestion)
            <button class="suggestion-chip px-4 py-2 rounded-[12px] border border-[rgba(0,212,170,0.1)] bg-transparent text-[13px] text-[#5a7a94] font-[inherit] transition-all duration-200 hover:border-[rgba(0,212,170,0.25)] hover:bg-[rgba(0,212,170,0.08)] hover:text-[#00d4aa] hover:-translate-y-[1px] cursor-pointer" 
                    onclick="selectSuggestion({{ $suggestion->id }}, this)">
                {{ $suggestion->message }}
            </button>
            @endforeach
        </div>
    </div>
</div>

<!-- Tooltips (handled via CSS pseudo-elements or JS) -->

@endsection

@section('styles')
<style>
    /* Animations */
    @keyframes slide-up {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fade-in {
        to { opacity: 1; }
    }

    @keyframes float-particle {
        0%, 100% { opacity: 0; transform: translateY(0px); }
        50% { opacity: 0.6; transform: translateY(-20px); }
    }

    @keyframes pulse-mic {
        0%, 100% { box-shadow: 0 0 0 0 rgba(0, 212, 170, 0.3); }
        50% { box-shadow: 0 0 0 8px rgba(0, 212, 170, 0); }
    }

    @keyframes tooltip-appear {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Apply animations */
    .animate-slide-up {
        animation: slide-up 0.8s cubic-bezier(0.22, 1, 0.36, 1) forwards;
    }

    .animate-fade-in {
        animation: fade-in 1s ease forwards;
        opacity: 0;
    }

    .animate-float-particle {
        animation: float-particle 8s ease-in-out infinite;
    }

    /* Tooltip styles */
    .tool-btn {
        position: relative;
    }

    .tool-btn::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%) translateY(4px);
        background: #1a2d42;
        color: #e8f0f8;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.2s ease;
        border: 1px solid rgba(0, 212, 170, 0.1);
        z-index: 100;
    }

    .tool-btn:hover::after {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    /* Tool button hover states */
    .tool-btn {
        background: transparent;
        color: #4a6a82;
        border: none;
        padding: 8px;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .tool-btn:hover {
        background: rgba(0, 212, 170, 0.15);
        color: #00d4aa;
        transform: translateY(-1px);
    }

    .tool-btn:active {
        transform: translateY(0);
    }

    /* Send button */
    .send-btn::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .send-btn:hover::after {
        opacity: 1;
    }

    .send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }

    /* Mic button recording animation */
    .mic-btn.recording {
        animation: pulse-mic 1.5s infinite;
    }

    /* Textarea scrollbar */
    #message-area::-webkit-scrollbar,
    .max-h-\[300px\]::-webkit-scrollbar {
        width: 4px;
    }

    #message-area::-webkit-scrollbar-track,
    .max-h-\[300px\]::-webkit-scrollbar-track {
        background: transparent;
    }

    #message-area::-webkit-scrollbar-thumb,
    .max-h-\[300px\]::-webkit-scrollbar-thumb {
        background: rgba(0, 212, 170, 0.2);
        border-radius: 2px;
    }

    /* Message bubbles */
    .message-bubble {
        max-width: 85%;
        word-wrap: break-word;
        line-height: 1.5;
    }

    .message-user {
        background: rgba(0, 212, 170, 0.15);
        color: #e8f0f8;
        border-radius: 18px 18px 4px 18px;
    }

    .message-assistant {
        background: rgba(255, 255, 255, 0.05);
        color: #e8f0f8;
        border-radius: 18px 18px 18px 4px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Suggestion chips hover */
    .suggestion-chip:hover {
        transform: translateY(-1px);
    }

    /* Focus visible for accessibility */
    button:focus-visible,
    textarea:focus-visible {
        outline: 2px solid #00d4aa;
        outline-offset: 2px;
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {
        .chat-container {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }

        .greeting h1 {
            font-size: 22px !important;
        }

        .suggestions {
            gap: 6px !important;
        }

        .suggestion-chip {
            font-size: 12px !important;
            padding: 6px 12px !important;
        }

        .message-bubble {
            max-width: 90%;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Auto-resize textarea
    const textarea = document.getElementById('message-input');
    const chatBox = document.getElementById('chat-box');
    const sendBtn = document.getElementById('send-btn');
    const micBtn = document.getElementById('mic-btn');
    const messageArea = document.getElementById('message-area');

    function autoResizeTextarea() {
        textarea.style.height = 'auto';
        const newHeight = Math.min(textarea.scrollHeight, 120);
        textarea.style.height = newHeight + 'px';
        
        // Adjust container if needed
        if (newHeight > 26) {
            chatBox.style.paddingBottom = '12px';
        } else {
            chatBox.style.paddingBottom = '12px';
        }
    }

    textarea.addEventListener('input', autoResizeTextarea);

    // Message counter
    function updateMessageCount() {
        const count = textarea.value.length;
        // Could add a counter display if desired
    }

    textarea.addEventListener('input', updateMessageCount);

    // Send message function
    async function sendMessage() {
        const message = textarea.value.trim();
        if (!message || sendBtn.disabled) return;

        // Disable send button
        sendBtn.disabled = true;
        sendBtn.style.transform = 'scale(0.85)';

        // Add user message to display
        addMessageToDisplay(message, 'user');

        // Clear and reset textarea
        textarea.value = '';
        autoResizeTextarea();

        try {
            // Send to server
            const response = await fetch('/chatui/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();

            if (data.success) {
                // Add assistant response
                setTimeout(() => {
                    addMessageToDisplay(data.assistant_response.content, 'assistant');
                }, 600);
            }
        } catch (error) {
            console.error('Error sending message:', error);
            addMessageToDisplay('Sorry, there was an error sending your message. Please try again.', 'assistant');
        }

        // Reset send button
        setTimeout(() => {
            sendBtn.disabled = false;
            sendBtn.style.transform = '';
        }, 150);

        // Focus back on textarea
        textarea.focus();
    }

    // Handle Enter key (Shift+Enter for newline)
    textarea.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Add message to display
    function addMessageToDisplay(message, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-bubble message-${type} p-3 mb-3 inline-block`;
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateY(10px)';
        messageDiv.style.transition = 'all 0.3s ease';
        
        // Basic XSS protection
        const cleanMessage = message
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        
        // Convert URLs to links
        const urlRegex = /(https?:\/\/[^\s]+)/g;
        const processedMessage = cleanMessage.replace(urlRegex, '<a href="$1" target="_blank" rel="noopener noreferrer" class="text-[#00d4aa] underline hover:no-underline">$1</a>');
        
        messageDiv.innerHTML = processedMessage;
        messageArea.appendChild(messageDiv);

        // Animate in
        setTimeout(() => {
            messageDiv.style.opacity = '1';
            messageDiv.style.transform = 'translateY(0)';
        }, 10);

        // Scroll to bottom
        messageArea.scrollTop = messageArea.scrollHeight;
    }

    // Select suggestion chip
    async function selectSuggestion(id, element) {
        try {
            const response = await fetch('/chatui/suggestion/' + id, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                // Populate textarea
                let message = data.message;
                // Remove emoji prefix if present
                message = message.replace(/^[✨📝💡🔍]\s*/, '');
                
                textarea.value = message;
                autoResizeTextarea();
                textarea.focus();

                // Animate chip
                element.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    element.style.transform = '';
                }, 150);
            }
        } catch (error) {
            console.error('Error selecting suggestion:', error);
        }
    }

    // Recording state
    let isRecording = false;
    let recordingId = null;

    async function toggleRecording() {
        if (isRecording) {
            // Stop recording
            try {
                const response = await fetch('/chatui/recording/stop', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ action: 'stop' })
                });

                const data = await response.json();

                if (data.success) {
                    micBtn.classList.remove('recording');
                    isRecording = false;
                    
                    // Add assistant response
                    addMessageToDisplay('I can see you were recording. How would you like me to help with this?', 'assistant');
                }
            } catch (error) {
                console.error('Error stopping recording:', error);
            }
        } else {
            // Start recording
            try {
                const response = await fetch('/chatui/recording/start', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ action: 'start' })
                });

                const data = await response.json();

                if (data.success) {
                    micBtn.classList.add('recording');
                    isRecording = true;
                    recordingId = data.recording_id;
                }
            } catch (error) {
                console.error('Error starting recording:', error);
            }
        }
    }

    // Toolbar actions
    function handleAttach() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*,.pdf,.doc,.docx,.txt';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                // Upload file
                uploadFile(file);
            }
        };
        input.click();
    }

    function uploadFile(file) {
        const formData = new FormData();
        formData.append('attachment', file);

        fetch('/chatui/upload', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addMessageToDisplay(`📎 ${file.name} uploaded successfully`, 'assistant');
            }
        })
        .catch(error => console.error('Error uploading file:', error));
    }

    function handleWebSearch() {
        const query = textarea.value.trim() || prompt('What would you like to search for?');
        if (query) {
            // Perform search
            fetch('/chatui/search?query=' + encodeURIComponent(query), {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let results = data.results.map(r => 
                        `<div class="p-3 mb-2 border border-[rgba(0,212,170,0.1)] rounded-lg hover:border-[rgba(0,212,170,0.25)] transition-colors">
                            <a href="${r.url}" target="_blank" class="text-[#00d4aa] hover:underline font-medium">${r.title}</a>
                            <p class="text-[#5a7a94] text-sm mt-1">${r.snippet}</p>
                        </div>`
                    ).join('');
                    
                    addMessageToDisplay(`Search results for: <strong>${escapeHtml(query)}</strong><br><br>${results}`, 'assistant');
                }
            });
        }
    }

    function handleImageGen() {
        textarea.value = 'Generate an image of ';
        autoResizeTextarea();
        textarea.focus();
    }

    function handleComponents() {
        alert('Opening component library...');
        window.location.href = '/components';
    }

    function handleTemplates() {
        alert('Opening template library...');
        window.location.href = '/templates';
    }

    function handleColorPalette() {
        alert('Opening color palette gallery...');
        // Could open a modal with color palette options
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Welcome animation
    setTimeout(() => {
        addMessageToDisplay('Hello! I\'m ready to help. Try one of the suggestions above or ask me anything!', 'assistant');
    }, 1000);
</script>
@endsection
