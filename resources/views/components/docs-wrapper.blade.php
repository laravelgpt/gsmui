
<div class="docs-wrapper" x-data="docsWrapper()">
    <!-- Tab Bar -->
    <div class="docs-tabs">
        <button 
            x-on:click="activeTab = 'preview'" 
            :class="activeTab === 'preview' ? 'active' : ''"
            class="tab-btn"
        >
            Preview
        </button>
        <button 
            x-on:click="activeTab = 'code'" 
            :class="activeTab === 'code' ? 'active' : ''"
            class="tab-btn"
        >
            Code
        </button>
        <div class="flex-1"></div>
        <button 
            x-on:click="copyCode()" 
            class="copy-btn"
            :class="copied ? 'copied' : ''"
        >
            <template x-if="!copied">
                <span class="flex items-center gap-1">
                    <x-icon.copy class="w-4 h-4" />
                    Copy
                </span>
            </template>
            <template x-if="copied">
                <span class="flex items-center gap-1 text-[#39FF14]">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Copied!
                </span>
            </template>
        </button>
    </div>

    <!-- Preview Panel -->
    <div x-show="activeTab === 'preview'" x-cloak class="docs-preview">
        {{ $slot }}
    </div>

    <!-- Code Panel -->
    <div x-show="activeTab === 'code'" x-cloak class="docs-code">
        <pre><code x-text="componentCode" class="language-html"></code></pre>
    </div>
</div>

<script>
function docsWrapper() {
    return {
        activeTab: 'preview',
        copied: false,
        componentCode: `{{ $code }}`,
        
        copyCode() {
            navigator.clipboard.writeText(this.componentCode).then(() => {
                this.copied = true;
                setTimeout(() => {
                    this.copied = false;
                }, 2000);
            });
        }
    }
}
</script>

<style>
.docs-wrapper {
    @apply border border-[rgba(0,212,255,0.2)] rounded-xl overflow-hidden;
    background: rgba(11, 15, 25, 0.8);
}

.docs-tabs {
    @apply flex items-center gap-2 p-2 border-b border-[rgba(0,212,255,0.1)] bg-[rgba(19,24,40,0.9)];
}

.tab-btn {
    @apply px-4 py-2 text-sm rounded-lg transition-all;
    background: transparent;
    color: var(--text-secondary);
    border: 1px solid transparent;
}

.tab-btn:hover {
    @apply text-[#00D4FF];
    border-color: rgba(0, 212, 255, 0.2);
}

.tab-btn.active {
    @apply text-[#00D4FF] bg-[rgba(0,212,255,0.1)];
    border-color: rgba(0, 212, 255, 0.3);
}

.copy-btn {
    @apply flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm transition-all;
    background: rgba(0, 212, 255, 0.1);
    color: var(--text-primary);
    border: 1px solid rgba(0, 212, 255, 0.2);
}

.copy-btn:hover {
    @apply bg-[rgba(0,212,255,0.2)];
    border-color: var(--electric-blue);
}

.copy-btn.copied {
    @apply bg-[rgba(57,255,20,0.15)] border-[#39FF14];
}

.docs-preview {
    @apply p-6 bg-[rgba(11,15,25,0.5)];
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.docs-code {
    @apply overflow-hidden;
}

.docs-code pre {
    @apply m-0 p-4 bg-[#0d1117] overflow-x-auto;
    border-top: 1px solid rgba(0, 212, 255, 0.1);
}

.docs-code code {
    @apply text-sm font-mono;
    color: var(--text-primary);
}

/* Prism.js theme override */
.language-html .token.tag { color: #ff7b72; }
.language-html .token.attr-name { color: #79c0ff; }
.language-html .token.attr-value { color: #a5d6ff; }
.language-html .token.punctuation { color: #c9d1d9; }
</style>
