
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSM-UI Component Playground</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tokens.css') }}">
    <style>
        .playground-splitter {
            resize: horizontal;
            overflow: auto;
            cursor: col-resize;
        }
        .code-editor {
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            font-size: 14px;
            line-height: 1.6;
        }
        .component-preview {
            min-height: 400px;
            padding: 2rem;
        }
        .prop-control {
            @apply mb-4 p-3 bg-white/5 rounded-lg border border-white/10;
        }
        .prop-label {
            @apply text-sm text-gray-400 mb-1 block;
        }
        .prop-input {
            @apply w-full bg-white/10 border border-white/20 rounded px-3 py-2 text-white focus:outline-none focus:border-electric-blue transition-colors;
        }
        .prop-select {
            @apply w-full bg-white/10 border border-white/20 rounded px-3 py-2 text-white focus:outline-none focus:border-electric-blue transition-colors appearance-none;
        }
    </style>
</head>
<body class="bg-deep-space text-white min-h-screen">
    <div class="flex h-screen">
        <!-- Controls Panel -->
        <div class="w-80 bg-white/5 border-r border-white/10 p-6 overflow-y-auto">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-electric-blue mb-2">GSM-UI Playground</h1>
                <p class="text-gray-400 text-sm">Live component testing environment</p>
            </div>

            <!-- Component Selection -->
            <div class="mb-6">
                <label class="prop-label">Component Type</label>
                <select id="componentType" class="prop-select" onchange="updateComponent()">
                    <option value="button">Button</option>
                    <option value="card">Card</option>
                    <option value="input">Input</option>
                    <option value="modal">Modal</option>
                    <option value="alert">Alert</option>
                    <option value="badge">Badge</option>
                    <option value="avatar">Avatar</option>
                    <option value="table">Table</option>
                </select>
            </div>

            <!-- Variant Control -->
            <div class="mb-6">
                <label class="prop-label">Variant</label>
                <select id="variant" class="prop-select" onchange="updateComponent()">
                    <option value="primary">Primary</option>
                    <option value="secondary">Secondary</option>
                    <option value="ghost">Ghost</option>
                    <option value="danger">Danger</option>
                    <option value="success">Success</option>
                </select>
            </div>

            <!-- Size Control -->
            <div class="mb-6">
                <label class="prop-label">Size</label>
                <select id="size" class="prop-select" onchange="updateComponent()">
                    <option value="sm">Small</option>
                    <option value="md">Medium</option>
                    <option value="lg">Large</option>
                    <option value="xl">Extra Large</option>
                </select>
            </div>

            <!-- Label Input -->
            <div class="mb-6">
                <label class="prop-label">Label / Text</label>
                <input type="text" id="label" class="prop-input" value="Click Me" oninput="updateComponent()">
            </div>

            <!-- State Controls -->
            <div class="mb-6 space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="disabled" class="w-4 h-4" onchange="updateComponent()">
                    <span class="text-sm">Disabled</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="loading" class="w-4 h-4" onchange="updateComponent()">
                    <span class="text-sm">Loading State</span>
                </label>
            </div>

            <!-- Dynamic Props (will be populated based on component) -->
            <div id="dynamicProps" class="mb-6"></div>

            <!-- Code Preview -->
            <div class="mb-6">
                <label class="prop-label">Generated Code</label>
                <div class="bg-black/50 rounded-lg p-4 font-mono text-xs overflow-x-auto">
                    <pre id="codePreview" class="text-green-400"></pre>
                </div>
            </div>
        </div>

        <!-- Preview Area -->
        <div class="flex-1 flex flex-col">
            <!-- Preview Toolbar -->
            <div class="bg-white/5 border-b border-white/10 p-4 flex items-center justify-between">
                <h2 class="font-semibold">Live Preview</h2>
                <div class="flex gap-2">
                    <button onclick="resetPreview()" class="px-4 py-2 bg-white/10 rounded hover:bg-white/20 transition-colors text-sm">
                        Reset
                    </button>
                    <button onclick="copyCode()" class="px-4 py-2 bg-electric-blue text-deep-space rounded hover:bg-blue-400 transition-colors text-sm font-medium">
                        Copy Code
                    </button>
                </div>
            </div>

            <!-- Component Preview -->
            <div id="previewArea" class="component-preview flex-1 overflow-auto">
                <div class="max-w-4xl mx-auto">
                    <div id="componentContainer" class="space-y-6">
                        <!-- Component will be rendered here -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-6">
                            <h3 class="text-electric-blue mb-4">Component Preview</h3>
                            <div id="liveComponent"></div>
                        </div>

                        <!-- Multiple Variants Demo -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-6">
                            <h3 class="text-electric-blue mb-4">All Variants</h3>
                            <div id="variantsDemo" class="flex flex-wrap gap-4 items-center"></div>
                        </div>

                        <!-- Size Comparison -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-6">
                            <h3 class="text-electric-blue mb-4">Size Comparison</h3>
                            <div id="sizeDemo" class="flex items-end gap-4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Component definitions
        const components = {
            button: {
                render: (props) => `
                    <button class="gsm-btn gsm-btn-${props.variant} gsm-btn-${props.size} px-4 py-2 rounded-lg font-medium transition-all duration-200 border-2 ${getVariantStyles(props.variant)} ${props.loading ? 'cursor-wait' : ''}" ${props.disabled ? 'disabled' : ''}>
                        ${props.loading ? `
                            <span class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                ${props.label}
                            </span>
                        ` : props.label}
                    </button>
                `,
                code: (props) => `<x-gsmui::components.ui.button label="${props.label}" variant="${props.variant}" size="${props.size}" />`
            },
            card: {
                render: (props) => `
                    <div class="bg-white/5 border border-white/10 rounded-xl p-6 backdrop-blur-sm hover:border-electric-blue/30 transition-colors">
                        <h3 class="text-xl font-semibold text-electric-blue mb-2">${props.label}</h3>
                        <p class="text-gray-400">This is a ${props.variant} card component with glassmorphism effect.</p>
                    </div>
                `,
                code: (props) => `<x-gsmui::components.ui.card title="${props.label}" variant="${props.variant}" />`
            },
            input: {
                render: (props) => `
                    <div class="space-y-2">
                        <label class="text-sm text-gray-400">${props.label}</label>
                        <input type="text" class="w-full bg-white/10 border ${props.variant === 'primary' ? 'border-electric-blue/50' : 'border-white/20'} rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:outline-none focus:border-electric-blue transition-colors" placeholder="Enter text...">
                    </div>
                `,
                code: (props) => `<x-gsmui::components.forms.input label="${props.label}" variant="${props.variant}" />`
            }
        };

        function getVariantStyles(variant) {
            const styles = {
                primary: 'bg-electric-blue/20 border-electric-blue/50 text-electric-blue hover:bg-electric-blue hover:text-deep-space',
                secondary: 'bg-toxic-green/20 border-toxic-green/50 text-toxic-green hover:bg-toxic-green hover:text-deep-space',
                ghost: 'bg-transparent border-indigo/50 text-indigo hover:bg-indigo hover:text-white',
                danger: 'bg-red-500/20 border-red-500/50 text-red-400 hover:bg-red-500 hover:text-white',
                success: 'bg-green-500/20 border-green-500/50 text-green-400 hover:bg-green-500 hover:text-white'
            };
            return styles[variant] || styles.primary;
        }

        function updateComponent() {
            const type = document.getElementById('componentType').value;
            const variant = document.getElementById('variant').value;
            const size = document.getElementById('size').value;
            const label = document.getElementById('label').value;
            const disabled = document.getElementById('disabled').checked;
            const loading = document.getElementById('loading').checked;

            const props = { type, variant, size, label, disabled, loading };

            // Update main component
            const component = components[type];
            if (component) {
                document.getElementById('liveComponent').innerHTML = component.render(props);
                document.getElementById('codePreview').textContent = component.code(props);
            }

            // Update variants demo
            updateVariantsDemo(type, props);
            updateSizeDemo(type, props);
        }

        function updateVariantsDemo(type, baseProps) {
            const variants = ['primary', 'secondary', 'ghost', 'danger', 'success'];
            const container = document.getElementById('variantsDemo');
            
            container.innerHTML = variants.map(v => {
                const props = { ...baseProps, variant: v };
                return components[type]?.render(props) || '';
            }).join('');
        }

        function updateSizeDemo(type, baseProps) {
            const sizes = ['sm', 'md', 'lg', 'xl'];
            const container = document.getElementById('sizeDemo');
            
            container.innerHTML = sizes.map(s => {
                const props = { ...baseProps, size: s };
                return `<div class="text-center">
                    <div class="mb-2">${components[type]?.render(props) || ''}</div>
                    <span class="text-xs text-gray-500">${s.toUpperCase()}</span>
                </div>`;
            }).join('');
        }

        function resetPreview() {
            document.getElementById('componentType').value = 'button';
            document.getElementById('variant').value = 'primary';
            document.getElementById('size').value = 'md';
            document.getElementById('label').value = 'Click Me';
            document.getElementById('disabled').checked = false;
            document.getElementById('loading').checked = false;
            updateComponent();
        }

        function copyCode() {
            const code = document.getElementById('codePreview').textContent;
            navigator.clipboard.writeText(code).then(() => {
                const btn = event.target;
                const original = btn.textContent;
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = original, 2000);
            });
        }

        // Initialize
        updateComponent();
    </script>
</body>
</html>
