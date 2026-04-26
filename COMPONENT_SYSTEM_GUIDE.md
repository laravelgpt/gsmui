
# Component System Guide

## Overview

The Laravel Starter Kit features a comprehensive multi-stack component architecture that allows you to build components once and use them across Blade, Livewire, Filament, React, and Vue.js stacks.

## Architecture

### Component Structure

```
app/Components/
├── Contracts/           # Interfaces & abstract classes
│   ├── ComponentInterface.php
│   ├── RenderableInterface.php
│   └── HasActionsInterface.php
├── Shared/              # Shared logic & base classes
│   ├── BaseComponent.php
│   ├── DataTableComponent.php
│   └── ComponentRegistry.php
├── Livewire/
│   ├── Volt/           # Volt functional components
│   └── Class/          # Class-based components
├── Blade/
│   ├── View/           # Blade views
│   └── Class/          # Component classes
├── Filament/
│   ├── Pages/          # Filament pages
│   ├── Widgets/        # Filament widgets
│   └── Resources/      # Filament resources
├── React/
│   ├── components/     # React components
│   └── hooks/          # Custom hooks
└── Vue/
    ├── components/     # Vue components
    └── composables/    # Vue composables
```

## Creating a New Component

### Step 1: Define the Shared Component

Create a shared component class that extends `BaseComponent`:

```php
<?php

namespace App\Components\Shared;

class CardComponent extends BaseComponent
{
    public function renderData(): array
    {
        return [
            'title' => $this->props['title'] ?? 'Default Title',
            'content' => $this->props['content'] ?? '',
            'footer' => $this->props['footer'] ?? null,
        ];
    }

    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'category' => 'layout',
            'variant' => 'card',
            'themeable' => true,
        ]);
    }

    public function getDefaultProps(): array
    {
        return [
            'title' => 'Card Title',
            'content' => 'Card content goes here.',
            'footer' => null,
            'theme' => 'default',
        ];
    }
}
```

### Step 2: Register the Component

Register your component in a service provider or boot method:

```php
use App\Components\Shared\ComponentRegistry;
use App\Components\Shared\CardComponent;

// Register for all stacks
ComponentRegistry::register('card', CardComponent::class, 'universal');

// Or register for specific stacks
ComponentRegistry::register('card', CardComponent::class, 'blade');
ComponentRegistry::register('card', CardComponent::class, 'livewire');
```

### Step 3: Create Stack-Specific Implementations

#### Blade Component

Create the Blade view: `resources/views/components/blade/card.blade.php`

```blade
@props(['title', 'content', 'footer', 'theme'])

<div class="card card-{{ $theme }}">
    <div class="card-header">
        <h3>{{ $title }}</h3>
    </div>
    <div class="card-body">
        {!! $content !!}
    </div>
    @if($footer)
        <div class="card-footer">
            {{ $footer }}
        </div>
    @endif
</div>
```

#### Livewire (Volt) Component

Create: `app/Components/Livewire/Volt/Card.php`

```php
<?php

use function Livewire\Volt\set;

set('title', 'Card Title');
set('content', 'Card content');
set('footer', null);

?>

<div class="card">
    <div class="card-header">
        <h3 wire:text="title"></h3>
    </div>
    <div class="card-body">
        <div wire:text="content"></div>
    </div>
    @if($footer)
        <div class="card-footer">
            <span wire:text="footer"></span>
        </div>
    @endif
</div>
```

#### React Component

Create: `app/Components/React/components/Card.jsx`

```jsx
import React from 'react';
import PropTypes from 'prop-types';

const Card = ({ title, content, footer, theme = 'default' }) => {
    return (
        <div className={`card card-${theme}`}>
            <div className="card-header">
                <h3>{title}</h3>
            </div>
            <div className="card-body">
                {content}
            </div>
            {footer && (
                <div className="card-footer">
                    {footer}
                </div>
            )}
        </div>
    );
};

Card.propTypes = {
    title: PropTypes.string.isRequired,
    content: PropTypes.node.isRequired,
    footer: PropTypes.node,
    theme: PropTypes.string
};

export default Card;
```

#### Vue Component

Create: `app/Components/Vue/components/Card.vue`

```vue
<template>
  <div :class="`card card-${theme}`">
    <div class="card-header">
      <h3>{{ title }}</h3>
    </div>
    <div class="card-body">
      <slot>{{ content }}</slot>
    </div>
    <div v-if="footer" class="card-footer">
      {{ footer }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    title: {
      type: String,
      required: true
    },
    content: {
      type: String,
      default: ''
    },
    footer: {
      type: String,
      default: null
    },
    theme: {
      type: String,
      default: 'default'
    }
  }
}
</script>
```

#### Filament Component

Create: `app/Components/Filament/Widgets/CardWidget.php`

```php
<?php

namespace App\Components\Filament\Widgets;

use Filament\Widgets\Widget;

class CardWidget extends Widget
{
    protected static string $view = 'components.filament.card';

    public ?string $title = 'Card Title';
    public ?string $content = '';
    public ?string $footer = null;
}
```

## Using Components

### In Blade Templates

```blade
{{-- Using the component class --}}
<x-app.components.blade.card 
    title="My Card" 
    content="Card content here"
    footer="Footer text"
/>

{{-- Using the renderer --}}
@php
    $card = new \App\Components\Shared\CardComponent([
        'title' => 'Dynamic Card',
        'content' => 'Generated content'
    ]);
@endphp
{!! $card->render('blade') !!}
```

### In Livewire (Volt)

```php
<?php

use function Livewire\Volt\livewire;

?>

<div>
    {{-- Using Volt component --}}
    <livewire:card 
        :title="'Dynamic Title'" 
        :content="'Dynamic content'" 
    />
</div>
```

### In React

```jsx
import Card from './components/Card';

function App() {
    return (
        <Card
            title="React Card"
            content={<p>Card content with JSX</p>}
            footer="Footer"
            theme="primary"
        />
    );
}
```

### In Vue

```vue
<template>
  <Card
    title="Vue Card"
    content="Card content"
    footer="Footer"
    theme="primary"
  />
</template>

<script>
import Card from './components/Card.vue';

export default {
  components: { Card }
}
</script>
```

### In Filament

```php
use App\Components\Filament\Widgets\CardWidget;

public function getHeaderWidgets(): array
{
    return [
        CardWidget::make([
            'title' => 'Dashboard Card',
            'content' => 'Card content',
        ]),
    ];
}
```

## Component Registry

The ComponentRegistry manages all registered components:

```php
use App\Components\Shared\ComponentRegistry;

// Register a component
ComponentRegistry::register('card', CardComponent::class, 'universal');

// Get component for a specific stack
$class = ComponentRegistry::get('card', 'blade');

// Check if component exists
if (ComponentRegistry::has('card', 'react')) {
    // Component exists for React
}

// Get all components for a stack
$components = ComponentRegistry::all('blade');

// Get components by category
$layoutComponents = ComponentRegistry::byCategory('layout');

// Render component
$html = ComponentRegistry::render('card', [
    'title' => 'Title',
    'content' => 'Content'
], 'blade');
```

## Best Practices

### 1. Keep Shared Logic in BaseComponent
- Put common functionality in `BaseComponent`
- Override only what's stack-specific

### 2. Use Configuration Arrays
- Define component configuration in `getConfig()`
- Include category, variant, theme, and other metadata

### 3. Validate Props
- Implement `validateProps()` in each component
- Ensure required props are present

### 4. Provide Sensible Defaults
- Define default props in `getDefaultProps()`
- Make components work without configuration

### 5. Support All Stacks
- Create implementations for all major stacks
- Fall back to universal components when stack-specific isn't available

### 6. Use Categories
- Organize components by category (layout, form, data, etc.)
- Makes them easier to find and use

### 7. Document Props
- Document all props with descriptions
- Include examples in documentation

### 8. Test All Stacks
- Write tests for each stack's implementation
- Ensure consistent behavior across stacks

## Component Props Standardization

### Common Props

```php
public function getDefaultProps(): array
{
    return [
        // Display
        'title' => null,
        'description' => null,
        
        // Styling
        'theme' => 'default',
        'variant' => 'base',
        'size' => 'md',
        
        // Behavior
        'disabled' => false,
        'readonly' => false,
        'required' => false,
        
        // Events
        'onClick' => null,
        'onChange' => null,
        
        // Additional
        'className' => '',
        'style' => [],
    ];
}
```

## Theming

Components should support theming:

```php
public function getConfig(): array
{
    return [
        'themeable' => true,
        'theme' => $this->props['theme'] ?? 'default',
        'variants' => [
            'default' => 'Default appearance',
            'primary' => 'Primary action style',
            'secondary' => 'Secondary action style',
        ],
    ];
}
```

## Responsive Design

All components should be responsive:

```php
public function getConfig(): array
{
    return [
        'responsive' => true,
        'breakpoints' => [
            'sm' => 640,
            'md' => 768,
            'lg' => 1024,
            'xl' => 1280,
        ],
    ];
}
```

## Accessibility

Ensure all components are accessible:

- Use semantic HTML
- Include ARIA attributes
- Support keyboard navigation
- Provide focus states
- Include screen reader text when needed

## Performance

- Lazy load non-critical components
- Memoize expensive computations
- Use React.memo() or Vue's computed properties
- Avoid unnecessary re-renders
- Optimize images and assets

## Internationalization

Support i18n in all components:

```php
public function renderData(): array
{
    return [
        'title' => __('components.card.title'),
        'content' => $this->props['content'],
    ];
}
```

## Examples

See the existing components in `app/Components/` for complete examples of:
- DataTable (cross-stack)
- Card (simple component)
- Form components (complex with validation)
- Modal (interactive component)
- Navigation (stateful component)

## Troubleshooting

### Component Not Found
- Ensure component is registered
- Check stack name is correct
- Verify class path is correct

### Props Not Working
- Check prop names match
- Verify default props are defined
- Ensure props are passed correctly for the stack

### Rendering Issues
- Check view paths are correct
- Verify Blade syntax is valid
- Ensure JavaScript is loaded for interactive components

### Stack-Specific Issues
- Review stack-specific requirements
- Check for missing dependencies
- Verify configuration is correct

## Next Steps

1. Add more components to the registry
2. Create stack-specific variants
3. Build component documentation site
4. Add component playground
5. Create component marketplace
6. Add component versioning
7. Build component testing suite

## Resources

- [Component Registry](./app/Components/Shared/ComponentRegistry.php)
- [Base Component](./app/Components/Shared/BaseComponent.php)
- [DataTable Example](./app/Components/Shared/DataTableComponent.php)
- [React Components](./app/Components/React/components/)
- [Vue Components](./app/Components/Vue/components/)
