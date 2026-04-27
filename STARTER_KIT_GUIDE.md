
# GSM-UI Component Starter Kit Guide

## Overview

The GSM-UI Laravel Package provides a comprehensive component system across 4 technology stacks with AI-promptable components.

## Quick Start

### Generate Component
```bash
# Single stack
php artisan gsmui:component Button --category=ui --variant=primary

# Multiple stacks
php artisan gsmui:component Card --category=ui --stacks=blade,react,vue

# All stacks
php artisan gsmui:component Modal --category=feedback --stacks=all
```

## Component Architecture

### Directory Structure
```
app/Components/
├── Blade/                    # Blade templates
│   ├── Class/               # PHP classes
│   └── View/                # Blade views
├── Livewire/Volt/           # Livewire Volt components
├── React/
│   ├── components/          # React components
│   └── hooks/              # Custom hooks
├── Vue/
│   ├── components/          # Vue components
│   └── composables/        # Vue composables
├── Shared/
│   ├── Types/              # TypeScript types
│   └── Interfaces/         # PHP interfaces
└── Contracts/
    ├── Renderable.php       # Renderable interface
    └── HasActions.php       # Actions interface
```

## Categories

| Category | Description | Examples |
|----------|-------------|----------|
| **ui** | Visual elements | Button, Card, Badge, Avatar |
| **forms** | Input controls | Input, Select, DatePicker |
| **navigation** | Navigation | Menu, Tab, Breadcrumb |
| **feedback** | User feedback | Alert, Toast, Modal |
| **data** | Data display | Table, Chart, List |
| **layout** | Layout | Container, Grid, Flex |
| **media** | Media | Image, Avatar, Carousel |
| **utilities** | Utilities | Spacer, Divider |

## AI Prompt Templates

### Generate Component
```
Create a [component] component for GSM-UI:
- Category: [category]
- Variant: [variant]
- Size: [size]
- Stacks: [blade|livewire|react|vue|all]
- Props: [list props]

Generate:
1. Blade template
2. Livewire Volt component
3. React component with TypeScript
4. Vue 3 component with Composition API
5. Documentation
```

### Usage Example Prompts
```
Generate primary button for GSM-UI:
php artisan gsmui:component PrimaryButton --category=ui --variant=primary --size=md

Generate form input:
php artisan gsmui:component TextInput --category=forms --variant=default

Generate data table:
php artisan gsmui:component DataTable --category=data --variant=striped
```

## Component Properties

### Common Props
```typescript
interface ComponentProps {
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger' | 'success';
  size?: 'sm' | 'md' | 'lg' | 'xl';
  disabled?: boolean;
  loading?: boolean;
  className?: string;
}
```

### Design Tokens
```css
--electric-blue: #00D4FF;    /* Primary accent */
--toxic-green: #39FF14;      /* Secondary accent */
--indigo: #6366F1;           /* Tertiary accent */
--deep-space: #0B0F19;       /* Background */
```

## Stacks Comparison

| Feature | Blade | Livewire | React | Vue |
|---------|-------|----------|-------|-----|
| SSR | ✅ | ✅ | ❌ | ❌ |
| Interactivity | Low | High | High | High |
| SEO | Excellent | Good | Requires SSR | Requires SSR |
| Learning Curve | Low | Medium | Medium | Low |

## Examples

### Button Component

**Blade:**
```blade
<x-gsmui::components.ui.button 
    label="Click Me" 
    variant="primary" 
    size="md"
/>
```

**React:**
```jsx
import { Button } from './components/ui/Button';

<Button 
    label="Click Me" 
    variant="primary" 
    size="md"
    onClick={() => console.log('Clicked!')}
/>
```

**Vue:**
```vue
<script setup>
import Button from './components/ui/Button.vue';
</script>

<template>
  <Button 
    label="Click Me" 
    variant="primary" 
    size="md"
    @click="handleClick"
  />
</template>
```

## CLI Commands

### Component Generation
```bash
# Basic
php artisan gsmui:component {name}

# With options
php artisan gsmui:component {name} \
    --category=ui \
    --variant=primary \
    --size=md \
    --stacks=all \
    --force
```

### Options
- `--category` - Component category
- `--variant` - Visual variant
- `--size` - Component size
- `--stacks` - Technology stacks (comma-separated)
- `--force` - Overwrite existing

## Testing

```bash
# Run all tests
php artisan gsmui:test

# Run specific category
php artisan gsmui:test --filter=Button
```

## Best Practices

1. ✅ Use consistent naming (PascalCase for classes, kebab-case for files)
2. ✅ Include TypeScript types for React/Vue
3. ✅ Add PHPDoc comments
4. ✅ Generate documentation
5. ✅ Follow Midnight Electric theme
6. ✅ Ensure WCAG 2.1 AA compliance
7. ✅ Implement loading states
8. ✅ Handle disabled states

## Troubleshooting

### Component Not Found
```bash
php artisan view:clear
php artisan cache:clear
```

### Missing Styles
```bash
npm install
npm run build
```

### TypeScript Errors
```bash
npm run type-check
```

## Resources

- [Component Library](README_GSMUI_PACKAGE.md)
- [API Documentation](docs/api.md)
- [Design System](https://docs.gsm-ui.com/design)
