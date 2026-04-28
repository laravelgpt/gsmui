
<?php

namespace GSMUI\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

/**
 * Bulk Component Generator for GSM-UI
 */
class GSMUIBulkGenerateCommand extends Command
{
    protected $signature = 'gsmui:bulk-generate 
                            {--count=100 : Number of components to generate}
                            {--categories=ui,forms : Comma-separated categories}
                            {--stacks=all : Comma-separated stacks (blade,livewire,react,vue,svelte)}
                            {--force : Overwrite existing components}';

    protected $description = 'Bulk generate GSM-UI components';

    protected $filesystem;
    protected $generated = 0;
    protected $skipped = 0;

    protected $componentDefinitions = [
        'ui' => [
            'button' => ['Primary action button', 'primary'],
            'card' => ['Content container', 'secondary'],
            'badge' => ['Status indicator', 'success'],
            'avatar' => ['User avatar', 'secondary'],
            'icon' => ['Icon display', 'primary'],
            'spacer' => ['Flexible spacer', 'ghost'],
            'divider' => ['Content separator', 'secondary'],
            'chip' => ['Tag/pill component', 'success'],
        ],
        'forms' => [
            'input' => ['Text input field', 'primary'],
            'textarea' => ['Multi-line text input', 'secondary'],
            'select' => ['Dropdown selector', 'primary'],
            'checkbox' => ['Boolean input', 'success'],
            'radio' => ['Single choice input', 'primary'],
            'switch' => ['Toggle switch', 'secondary'],
            'datepicker' => ['Date selection', 'primary'],
            'fileupload' => ['File upload field', 'secondary'],
        ],
        'navigation' => [
            'menu' => ['Navigation menu', 'primary'],
            'tab' => ['Tabbed interface', 'secondary'],
            'breadcrumb' => ['Navigation trail', 'ghost'],
            'sidebar' => ['Collapsible sidebar', 'primary'],
            'navbar' => ['Top navigation bar', 'secondary'],
            'pagination' => ['Page navigation', 'primary'],
            'breadcrumb' => ['Navigation hierarchy', 'ghost'],
        ],
        'feedback' => [
            'alert' => ['Alert message', 'warning'],
            'toast' => ['Temporary notification', 'info'],
            'modal' => ['Overlay dialog', 'primary'],
            'dialog' => ['Confirmation dialog', 'danger'],
            'popover' => ['Floating content', 'secondary'],
            'tooltip' => ['Hover hint', 'info'],
            'loader' => ['Loading indicator', 'primary'],
            'skeleton' => ['Loading placeholder', 'ghost'],
        ],
        'data' => [
            'table' => ['Data table', 'primary'],
            'list' => ['Item list', 'secondary'],
            'chart' => ['Data visualization', 'primary'],
            'stat' => ['Statistic display', 'success'],
            'progress' => ['Progress indicator', 'primary'],
            'timeline' => ['Event timeline', 'secondary'],
        ],
        'layout' => [
            'container' => ['Content container', 'primary'],
            'grid' => ['Grid layout', 'secondary'],
            'flex' => ['Flex layout', 'primary'],
            'section' => ['Content section', 'ghost'],
            'divider' => ['Section divider', 'secondary'],
        ],
        'media' => [
            'image' => ['Image display', 'primary'],
            'avatar' => ['User avatar', 'secondary'],
            'video' => ['Video player', 'primary'],
            'gallery' => ['Image gallery', 'secondary'],
            'carousel' => ['Content carousel', 'primary'],
        ],
        'utilities' => [
            'spacer' => ['Flexible space', 'secondary'],
            'scroll' => ['Scroll control', 'ghost'],
            'overlay' => ['Background overlay', 'dark'],
        ],
    ];

    public function __construct(Filesystem $filesystem)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $count = (int) $this->option('count');
        $categories = explode(',', $this->option('categories'));
        $stacks = $this->option('stacks') === 'all'
            ? ['blade', 'livewire', 'react', 'vue', 'svelte']
            : explode(',', $this->option('stacks'));

        $this->info("\n📦 Bulk Component Generation");
        $this->info("   Count: {$count}");
        $this->info("   Categories: " . implode(', ', $categories));
        $this->info("   Stacks: " . implode(', ', $stacks));
        $this->info("   Force: " . ($this->option('force') ? 'Yes' : 'No'));
        $this->info("\n" . str_repeat('─', 50) . "\n");

        $availableDefinitions = [];
        foreach ($categories as $category) {
            if (isset($this->componentDefinitions[$category])) {
                $availableDefinitions = array_merge($availableDefinitions, 
                    $this->componentDefinitions[$category]);
            }
        }

        $definitionKeys = array_keys($availableDefinitions);
        $totalDefinitions = count($definitionKeys);

        if ($totalDefinitions === 0) {
            $this->error("No components found for categories: " . implode(', ', $categories));
            return 1;
        }

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        for ($i = 0; $i < $count; $i++) {
            $definitionKey = $definitionKeys[array_rand($definitionKeys)];
            [$description, $defaultVariant] = $availableDefinitions[$definitionKey];
            
            $className = Str::studly($definitionKey) . ($i + 1);
            $variant = $defaultVariant;
            $size = ['sm', 'md', 'lg'][array_rand(['sm', 'md', 'lg'])];
            
            foreach ($stacks as $stack) {
                $this->generateForStack($className, $definitionKey, $variant, $size, $stack);
            }
            
            $this->generated++;
            $bar->advance();
        }

        $bar->finish();
        
        $this->info("\n\n" . str_repeat('─', 50));
        $this->info("  Generated: {$this->generated} components");
        $this->info("  Skipped: {$this->skipped} components");
        $this->info("  Total files: " . ($this->generated * count($stacks)));
        $this->info(str_repeat('─', 50) . "\n");

        return 0;
    }

    protected function generateForStack($className, $definitionKey, $variant, $size, $stack)
    {
        switch ($stack) {
            case 'blade':
                $this->generateBlade($className, $definitionKey, $variant, $size);
                break;
            case 'livewire':
                $this->generateLivewire($className, $definitionKey, $variant, $size);
                break;
            case 'react':
                $this->generateReact($className, $definitionKey, $variant, $size);
                break;
            case 'vue':
                $this->generateVue($className, $definitionKey, $variant, $size);
                break;
            case 'svelte':
                $this->generateSvelte($className, $definitionKey, $variant, $size);
                break;
        }
    }

    protected function generateBlade($className, $definitionKey, $variant, $size)
    {
        $path = base_path("app/Components/Blade/Class/{$className}.php");
        if (!$this->option('force') && $this->filesystem->exists($path)) {
            $this->skipped++;
            return;
        }

        $stub = <<<PHP
<?php

namespace App\Components\Blade\Class;

class {$className}
{
    public string \$variant = '{$variant}';
    public string \$size = '{$size}';
    public string \$label;

    public function __construct(string \$label = null)
    {
        \$this->label = \$label ?? '{$className}';
    }

    public function render(): string
    {
        return view('components.blade.{$definitionKey}', [
            'variant' => \$this->variant,
            'size' => \$this->size,
            'label' => \$this->label,
        ])->render();
    }
}
PHP;

        $this->filesystem->ensureDirectoryExists(dirname($path));
        $this->filesystem->put($path, $stub);
    }

    protected function generateLivewire($className, $definitionKey, $variant, $size)
    {
        $path = base_path("app/Components/Livewire/Volt/{$className}.php");
        if (!$this->option('force') && $this->filesystem->exists($path)) {
            $this->skipped++;
            return;
        }

        $stub = <<<PHP
<?php

namespace App\Components\Livewire\Volt;

use Livewire\Component;

class {$className} extends Component
{
    public string \$variant = '{$variant}';
    public string \$size = '{$size}';
    public string \$label;

    public function mount(string \$label = null)
    {
        \$this->label = \$label ?? '{$className}';
    }

    public function render()
    {
        return view('components.livewire.{$definitionKey}');
    }
}
PHP;

        $this->filesystem->ensureDirectoryExists(dirname($path));
        $this->filesystem->put($path, $stub);
    }

    protected function generateReact($className, $definitionKey, $variant, $size)
    {
        $path = base_path("app/Components/React/components/{$className}.jsx");
        if (!$this->option('force') && $this->filesystem->exists($path)) {
            $this->skipped++;
            return;
        }

        $stub = <<<JSX
import React from 'react';
import { cva } from 'class-variance-authority';
import { clsx } from 'clsx';

const {$className}Variants = cva(
  'gsm-component gsm-{$definitionKey} border-2 transition-all duration-200',
  {
    variants: {
      variant: {
        primary: 'text-[#00D4FF] border-[#00D4FF] bg-[#0B0F19] hover:bg-[#00D4FF] hover:text-[#0B0F19]',
        secondary: 'text-[#39FF14] border-[#39FF14] bg-transparent hover:bg-[#39FF14]/10',
        ghost: 'text-[#6366F1] border-transparent hover:bg-[#6366F1]/10',
      },
      size: {
        sm: 'text-sm px-3 py-1.5',
        md: 'text-base px-4 py-2',
        lg: 'text-lg px-6 py-3',
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
  className,
  ...props 
}) => {
  return (
    <button
      type="button"
      className={clsx($classNameVariants({ variant, size, className }))}
      disabled={disabled}
      {...props}
    >
      {label}
    </button>
  );
});

{$className}.displayName = '{$className}';
JSX;

        $this->filesystem->ensureDirectoryExists(dirname($path));
        $this->filesystem->put($path, $stub);
    }

    protected function generateVue($className, $definitionKey, $variant, $size)
    {
        $path = base_path("app/Components/Vue/components/{$className}.vue");
        if (!$this->option('force') && $this->filesystem->exists($path)) {
            $this->skipped++;
            return;
        }

        $stub = <<<VUE
<template>
  <component
    :is="as"
    :class="componentClass"
    :disabled="disabled"
    @click="\$emit('click')"
    v-bind="\$attrs"
  >
    <slot>{{ label }}</slot>
  </component>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  label: { type: String, default: '{$className}' },
  variant: { type: String, default: '{$variant}' },
  size: { type: String, default: '{$size}' },
  disabled: { type: Boolean, default: false },
  as: { type: String, default: 'button' },
  className: { type: String, default: '' },
});

const emit = defineEmits(['click']);

const componentClass = computed(() => {
  const classes = [
    'gsm-component',
    'gsm-{$definitionKey}',
    'border-2',
    'transition-all',
    'duration-200',
  ];

  const variantColors = {
    primary: { text: '[#00D4FF]', border: '[#00D4FF]', bg: '[#0B0F19]', hoverBg: '[#00D4FF]' },
    secondary: { text: '[#39FF14]', border: '[#39FF14]', bg: 'transparent', hoverBg: '[#39FF14]/10' },
    ghost: { text: '[#6366F1]', border: 'transparent', bg: 'transparent', hoverBg: '[#6366F1]/10' },
  };

  const v = variantColors[props.variant] || variantColors.primary;
  
  classes.push(\`text\${v.text} border\${v.border} bg\${v.bg} hover:bg\${v.hoverBg}\`);

  const sizes = {
    sm: 'text-sm px-3 py-1.5',
    md: 'text-base px-4 py-2',
    lg: 'text-lg px-6 py-3',
  };
  classes.push(sizes[props.size] || sizes.md);

  if (props.disabled) {
    classes.push('opacity-50', 'cursor-not-allowed');
  }

  if (props.className) {
    classes.push(props.className);
  }

  return classes.join(' ');
});
</script>
VUE;

        $this->filesystem->ensureDirectoryExists(dirname($path));
        $this->filesystem->put($path, $stub);
    }

    protected function generateSvelte($className, $definitionKey, $variant, $size)
    {
        $path = base_path("app/Components/Svelte/{$className}.svelte");
        if (!$this->option('force') && $this->filesystem->exists($path)) {
            $this->skipped++;
            return;
        }

        $stub = <<<SVELTE
<script lang="ts">
  export let label: string = '{$className}';
  export let variant: 'primary' | 'secondary' | 'ghost' = '{$variant}';
  export let size: 'sm' | 'md' | 'lg' = '{$size}';
  export let disabled: boolean = false;
  
  const variants = {
    primary: 'bg-electric-blue text-deep-space hover:bg-blue-400',
    secondary: 'bg-toxic-green text-deep-space hover:bg-green-400',
    ghost: 'bg-transparent text-indigo hover:bg-indigo-100',
  };
  
  const sizes = {
    sm: 'text-sm px-3 py-1.5',
    md: 'text-base px-4 py-2',
    lg: 'text-lg px-6 py-3',
  };
  
  let className = '';
  export { className as class };
</script>

<button
  class="gsm-btn gsm-btn-{{variant}} gsm-btn-{{size}} font-medium rounded-lg transition-all duration-200 border-2 {{variants[variant]}} {{sizes[size]}} {{className}}"
  disabled={{disabled}}
  on:click
>
  {{label}}
</button>

<style>
  .gsm-btn {
    @apply font-medium rounded-lg transition-all duration-200 border-2;
}