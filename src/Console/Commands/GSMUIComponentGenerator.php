
<?php

namespace GSMUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * GSM-UI Component Generator
 * Generates components across 4 technology stacks
 */
class GSMUIComponentGenerator extends Command
{
    protected $signature = 'gsmui:component 
                            {name : Component name (e.g., Button, Card, Modal)}
                            {--category=utilities : Component category (ui, forms, navigation, feedback, data, layout, media)}
                            {--variant=default : Component variant (primary, secondary, ghost, etc.)}
                            {--size=md : Component size (sm, md, lg, xl)}
                            {--stacks=all : Comma-separated stacks (blade,livewire,react,vue) or "all"}
                            {--force : Overwrite existing files}';

    protected $description = 'Generate GSM-UI components across multiple technology stacks';

    protected $filesystem;
    protected $basePath;
    protected $stacks = [
        'blade' => [
            'path' => 'app/Components/Blade',
            'stub' => 'stubs/component.blade.stub'
        ],
        'livewire' => [
            'path' => 'app/Components/Livewire/Volt',
            'stub' => 'stubs/component.livewire.stub'
        ],
        'react' => [
            'path' => 'app/Components/React/components',
            'stub' => 'stubs/component.react.stub'
        ],
        'vue' => [
            'path' => 'app/Components/Vue/components',
            'stub' => 'stubs/component.vue.stub'
        ]
    ];

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->basePath = base_path();
    }

    public function handle()
    {
        $name = $this->argument('name');
        $category = $this->option('category');
        $variant = $this->option('variant');
        $size = $this->option('size');
        $selectedStacks = $this->option('stacks');

        // Validate category
        $validCategories = ['ui', 'forms', 'navigation', 'feedback', 'data', 'layout', 'media', 'utilities'];
        if (!in_array($category, $validCategories)) {
            $this->error("Invalid category. Valid: " . implode(', ', $validCategories));
            return 1;
        }

        // Determine which stacks to generate
        $stacksToGenerate = ($selectedStacks === 'all')
            ? array_keys($this->stacks)
            : explode(',', $selectedStacks);

        $this->info("\n📦 Generating component: <comment>{$name}</comment>");
        $this->info("   Category: {$category} | Variant: {$variant} | Size: {$size}");
        $this->info("   Stacks: " . implode(', ', $stacksToGenerate) . "\n");

        $generated = 0;
        $skipped = 0;

        foreach ($stacksToGenerate as $stack) {
            if (!isset($this->stacks[$stack])) {
                $this->warn("  ⚠️  Unknown stack: {$stack}");
                continue;
            }

            if ($this->generateComponent($name, $category, $variant, $size, $stack)) {
                $generated++;
                $this->info("  ✅ {$stack}");
            } else {
                $skipped++;
                $this->warn("  ⚠️  {$stack} (skipped)");
            }
        }

        // Generate documentation
        $this->generateDocumentation($name, $category, $variant, $size);

        // Generate CLI stub
        $this->generateCLIStub($name, $category);

        $this->info("\n" . str_repeat('─', 50));
        $this->info("  Generated: {$generated} | Skipped: {$skipped}");
        $this->info(str_repeat('─', 50) . "\n");

        return 0;
    }

    protected function generateComponent($name, $category, $variant, $size, $stack)
    {
        $config = $this->stacks[$stack];
        $className = Str::studly($name);
        $componentName = Str::kebab($name);
        $categoryPath = Str::plural($category);

        $path = "{$this->basePath}/{$config['path']}/{$categoryPath}/{$className}.php";

        if (!$this->option('force') && $this->filesystem->exists($path)) {
            return false;
        }

        $stubContent = $this->getStubContent($stack, $className, $componentName, $category, $variant, $size);

        // Create subdirectories if needed
        $directory = dirname($path);
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }

        $this->filesystem->put($path, $stubContent);
        return true;
    }

    protected function getStubContent($stack, $className, $componentName, $category, $variant, $size)
    {
        $variants = [
            'primary' => ['color' => '#00D4FF', 'bg' => '#0B0F19'],
            'secondary' => ['color' => '#39FF14', 'bg' => '#1a1a1a'],
            'ghost' => ['color' => '#6366F1', 'bg' => 'transparent'],
            'danger' => ['color' => '#FF4757', 'bg' => '#1a0000'],
            'success' => ['color' => '#2ED573', 'bg' => '#0a1a0a'],
        ];

        $variantConfig = $variants[$variant] ?? $variants['primary'];
        $props = "\${$category}Props";

        switch ($stack) {
            case 'blade':
                return view("components.{$category}.{$componentName}", [
                    'variant' => $variant,
                    'size' => $size,
                    'class' => "gsm-btn gsm-btn-{$variant} gsm-btn-{$size}",
                ])->render();

            case 'livewire':
                return <<<LIVEWIRE
<?php

namespace App\Components\Livewire\Volt\n                . Str::plural(Str::studly($category)) . ";

use Livewire\Component;

class {$className} extends Component
{
    public \$variant = '{$variant}';
    public \$size = '{$size}';
    public \$label;
    public \$disabled = false;
    public \$loading = false;

    protected function rules()
    {
        return [
            'variant' => 'in:primary,secondary,ghost,danger,success',
            'size' => 'in:sm,md,lg,xl',
        ];
    }

    public function mount(\$label = null, \$variant = null, \$size = null)
    {
        \$this->label = \$label ?? Str::title('{$name}');
        \$this->variant = \$variant ?? \$this->variant;
        \$this->size = \$size ?? \$this->size;
    }

    public function render()
    {
        return view('components.livewire.{$category}.{$componentName}');
    }

    #[On('{$componentName}-action')]
    public function handleAction(\$data)
    {
        \$this->dispatch('{$componentName}-event', data: \$data);
    }
}
LIVEWIRE;

            case 'react':
                return <<<REACT
import React, { useState, useCallback } from 'react';
import { cva } from 'class-variance-authority';
import { clsx } from 'clsx';

const {$className}Variants = cva(
  'gsm-component gsm-{$componentName} border-2 transition-all duration-200',
  {
    variants: {
      variant: {
        primary: 'text-[{$variantConfig['color']}] border-[{$variantConfig['color']}] bg-[{$variantConfig['bg']}] hover:bg-[{$variantConfig['color']}] hover:text-[{$variantConfig['bg']}]',
        secondary: 'text-[{$variantConfig['color']}] border-[{$variantConfig['color']}] bg-transparent hover:bg-[{$variantConfig['color']}]/10',
        ghost: 'text-[{$variantConfig['color']}] border-transparent hover:bg-[{$variantConfig['color']}]/10',
      },
      size: {
        sm: 'text-sm px-3 py-1.5',
        md: 'text-base px-4 py-2',
        lg: 'text-lg px-6 py-3',
        xl: 'text-xl px-8 py-4',
      },
    },
    defaultVariants: {
      variant: '{$variant}',
      size: '{$size}',
    },
  }
);

export const {$className} = React.memo(({ 
  label = '{$className}',
  variant = '{$variant}',
  size = '{$size}',
  disabled = false,
  loading = false,
  onClick,
  className,
  ...props 
}) => {
  const [isLoading, setIsLoading] = useState(loading);

  const handleClick = useCallback(async (e) => {
    if (disabled || isLoading) return;
    
    if (onClick) {
      try {
        setIsLoading(true);
        await onClick(e);
      } finally {
        setIsLoading(false);
      }
    }
  }, [onClick, disabled, isLoading]);

  return (
    <button
      type="button"
      className={clsx($classNameVariants({ variant, size, className }))}
      disabled={disabled || isLoading}
      onClick={handleClick}
      aria-busy={isLoading}
      aria-label={label}
      {...props}
    >
      {isLoading ? (
        <span className="flex items-center gap-2">
          <svg className="animate-spin h-4 w-4" viewBox="0 0 24 24">
            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" fill="none"/>
            <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
          </svg>
          {label}
        </span>
      ) : label}
    </button>
  );
});

{$className}.displayName = '{$className}';
REACT;

            case 'vue':
                return <<<VUE
<template>
  <component
    :is="as"
    :class="componentClass"
    :disabled="disabled"
    :aria-busy="loading"
    :aria-label="label"
    @click="handleClick"
    v-bind="$attrs"
  >
    <div v-if="loading" class="flex items-center gap-2">
      <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
      </svg>
      {{ label }}
    </div>
    <template v-else>
      <slot>{{ label }}</slot>
    </template>
  </component>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  label: { type: String, default: '{$className}' },
  variant: { type: String, default: '{$variant}', validator: v => ['primary', 'secondary', 'ghost', 'danger', 'success'].includes(v) },
  size: { type: String, default: '{$size}', validator: v => ['sm', 'md', 'lg', 'xl'].includes(v) },
  disabled: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  as: { type: String, default: 'button' },
});

const emit = defineEmits(['click', '{$componentName}-action']);

const componentClass = computed(() => {
  const classes = [
    'gsm-component',
    'gsm-{$componentName}',
    'border-2',
    'transition-all',
    'duration-200',
  ];

  // Variant classes
  const variantColors = {
    primary: { text: '[#00D4FF]', border: '[#00D4FF]', bg: '[#0B0F19]', hoverBg: '[#00D4FF]' },
    secondary: { text: '[#39FF14]', border: '[#39FF14]', bg: 'transparent', hoverBg: '[#39FF14]/10' },
    ghost: { text: '[#6366F1]', border: 'transparent', bg: 'transparent', hoverBg: '[#6366F1]/10' },
  };

  const v = variantColors[props.variant] || variantColors.primary;
  
  classes.push(\`text\${v.text} border\${v.border} bg\${v.bg} hover:bg\${v.hoverBg}\`);

  // Size classes
  const sizes = {
    sm: 'text-sm px-3 py-1.5',
    md: 'text-base px-4 py-2',
    lg: 'text-lg px-6 py-3',
    xl: 'text-xl px-8 py-4',
  };
  classes.push(sizes[props.size] || sizes.md);

  if (props.disabled || props.loading) {
    classes.push('opacity-50', 'cursor-not-allowed');
  }

  if (props.className) {
    classes.push(props.className);
  }

  return classes.join(' ');
});

const handleClick = (e) => {
  if (props.disabled || props.loading) return;
  emit('click', e);
  emit('{$componentName}-action', { label: props.label, timestamp: Date.now() });
};
</script>
VUE;

            default:
                return "// Component stub for {$className}\n";
        }
    }

    protected function generateDocumentation($name, $category, $variant, $size)
    {
        $className = Str::studly($name);
        $componentName = Str::kebab($name);
        
        $docPath = "{$this->basePath}/resources/views/components/docs/{$componentName}.md";
        
        if (!$this->option('force') && $this->filesystem->exists($docPath)) {
            return;
        }

        $documentation = <<<DOCS
# {$className} Component

**Category:** {$category}  
**Variants:** primary, secondary, ghost, danger, success  
**Sizes:** sm, md, lg, xl

## Usage

### Blade
\`\`\`blade
<x-gsmui::components.{$category}.{$componentName} 
    label="{$className}" 
    variant="{$variant}" 
    size="{$size}"
/>
\`\`\`

### Livewire Volt
\`\`\`php
<livewire:{$category}.{$componentName}
    label="{$className}"
    variant="{$variant}"
    size="{$size}"
/>
\`\`\`

### React
\`\`\`jsx
import { {$className} } from './components/{$category}/{$className}';

<{$className} 
    label="{$className}"
    variant="{$variant}"
    size="{$size}"
/>
\`\`\`

### Vue
\`\`\`vue
<script setup>
import {$className} from './components/{$category}/{$className}.vue';
</script>

<template>
  <{$className}
    label="{$className}"
    variant="{$variant}"
    size="{$size}"
  />
</template>
\`\`\`

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| label | string | '{$className}' | Button label text |
| variant | string | '{$variant}' | Visual variant |
| size | string | '{$size}' | Component size |
| disabled | boolean | false | Disable interaction |
| loading | boolean | false | Show loading state |

## Events

- `{$componentName}-action` - Emitted on interaction
- `click` - Native click event

## Examples

\`\`\`blade
<!-- Primary button -->
<x-gsmui::components.utilities.{$componentName} variant="primary" size="md" />

<!-- Danger button with loading -->
<x-gsmui::components.utilities.{$componentName} variant="danger" loading />

<!-- Ghost button -->
<x-gsmui::components.utilities.{$componentName} variant="ghost" />
\`\`\`

## Design Token Mapping

- **Primary**: Electric Blue (#00D4FF)
- **Secondary**: Toxic Green (#39FF14)
- **Ghost**: Indigo (#6366F1)
- **Background**: Deep Space (#0B0F19)

DOCS;

        $this->filesystem->put($docPath, $documentation);
    }

    protected function generateCLIStub($name, $category)
    {
        $className = Str::studly($name);
        $stubPath = "{$this->basePath}/stubs/{$category}/{$className}.stub";
        
        if (!$this->option('force') && $this->filesystem->exists($stubPath)) {
            return;
        }

        $directory = dirname($stubPath);
        if (!$this->filesystem->exists($directory)) {
            $this->filesystem->makeDirectory($directory, 0755, true);
        }

        $this->filesystem->put($stubPath, "// Stub for {$className} component\n");
    }
}
