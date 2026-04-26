
# 🛠️ GSM-UI CLI Command Reference

## Overview

GSM-UI provides powerful command-line tools to manage and extend the component library.

---

## Available Commands

### 1. `gsm:add` - Download Components

Download and install components from the GSM-UI marketplace to your local project.

#### Syntax
```bash
php artisan gsm:add {component} [--token=API_TOKEN] [--local]
```

#### Arguments
- `component` - The component slug to download (required)

#### Options
- `--token=` - Personal Access Token for authentication
- `--local` - Use local API (for development)

#### Examples

Download a component:
```bash
php artisan gsm:add button
```

Download with token:
```bash
php artisan gsm:add datatable --token=pat_1234567890abcdef
```

Use local API:
```bash
php artisan gsm:add card --local
```

#### Error Codes
- `401` - Authentication failed (invalid token)
- `403` - Access denied (premium component without subscription)
- `404` - Component not found

---

### 2. `gsm:component` - Create New Components

Generate new components across all technology stacks (Blade, Livewire, React, Vue).

#### Syntax
```bash
php artisan gsm:component {name} [--category=] [--variant=] [--size=] [--type=] [--force]
```

#### Arguments
- `name` - The name of the component (e.g., "Button", "DataTable")

#### Options
- `--category=` - Component category
  - Available: `data-display`, `forms`, `navigation`, `feedback`, `layout`, `media`, `utilities`
  - Default: `utilities`

- `--variant=` - Default visual variant
  - Available: `primary`, `danger`, `ghost`
  - Default: `primary`

- `--size=` - Default size option
  - Available: `sm`, `md`, `lg`
  - Default: `md`

- `--type=` - Technology stack(s) to generate
  - Available: `blade`, `volt`, `react`, `vue`, `all`
  - Default: `all`

- `--force` - Overwrite existing files
  - Default: false

#### Examples

Create a simple utility component:
```bash
php artisan gsm:component Badge
```

Create a form component:
```bash
php artisan gsm:component Input --category=forms
```

Create a ghost variant component:
```bash
php artisan gsm:component IconButton --variant=ghost
```

Create only Blade and React components:
```bash
php artisan gsm:component Card --type=blade
php artisan gsm:component Card --type=react
```

Force overwrite existing component:
```bash
php artisan gsm:component Button --force
```

Create a danger button:
```bash
php artisan gsm:component DangerButton --variant=danger --size=lg
```

#### Generated Files

When you run `gsm:component`, the following files are created:

**Blade Component:**
- `resources/views/components/blade/{category}/{slug}.blade.php`

**Livewire Volt Component:**
- `app/Components/Livewire/Volt/{Name}.php`

**React Component:**
- `app/Components/React/components/{Name}.jsx`

**Vue Component:**
- `app/Components/Vue/components/{Name}.vue`

**Stub File:**
- `stubs/{slug}.stub`

**Documentation:**
- `resources/views/components/docs/{slug}.md`

#### Component Structure

All generated components include:

✅ **Props/Attributes**
- `label` - Display text
- `variant` - Visual style (primary, danger, ghost)
- `size` - Size variant (sm, md, lg)
- `icon` - Optional icon
- `iconPosition` - Icon placement (left, right)
- `loading` - Loading state
- `disabled` - Disabled state
- `fullWidth` - Full width option
- `type` - Button type (button, submit, reset)
- `href` - Optional link (converts to `<a>` tag)

✅ **Features**
- Glassmorphism styling
- Neon glow effects
- Hover animations
- Focus indicators
- Loading spinner
- Icon support
- Responsive design

✅ **Accessibility**
- Keyboard navigation
- Focus management
- ARIA attributes
- Screen reader support

#### Usage After Generation

**Blade:**
```blade
<x-components.blade.{category}.{slug} 
    label="Click Me" 
    variant="primary" 
    size="md" 
/>
```

**Livewire Volt:**
```blade
<livewire:{name} 
    label="Click Me" 
    variant="primary" 
    size="md" 
/>
```

**React:**
```jsx
import {Name} from './components/{Name}';

<Name 
    label="Click Me" 
    variant="primary" 
    size="md" 
/>
```

**Vue:**
```vue
<template>
  <{Name} 
    label="Click Me" 
    variant="primary" 
    size="md" 
  />
</template>

<script setup>
import {Name} from './components/{Name}.vue';
</script>
```

---

## Command Aliases

For convenience, you can use shorter aliases:

```bash
php artisan gsm:add          # Download component
php artisan gsm:component    # Create component
```

---

## Configuration

### Personal Access Token

Set your PAT in `.env`:

```ini
GSM_PERSONAL_ACCESS_TOKEN=your_token_here
```

Or pass via option:

```bash
php artisan gsm:add component --token=your_token_here
```

### API Endpoint

Configure the API base URL:

```ini
GSM_API_URL=https://api.gsm-ui.example.com
```

Or use local development:

```bash
php artisan gsm:add component --local
```

---

## Tips & Tricks

### Batch Download

Download multiple components:
```bash
php artisan gsm:add button && \
php artisan gsm:add card && \
php artisan gsm:add input
```

### Create Component Series

Generate related components:
```bash
php artisan gsm:component Alert --category=feedback
php artisan gsm:component Toast --category=feedback
php artisan gsm:component Modal --category=feedback
```

### Consistent Styling

Use same variant and size for consistency:
```bash
php artisan gsm:component PrimaryButton --variant=primary --size=lg
php artisan gsm:component SecondaryButton --variant=ghost --size=lg
```

### Team Development

Share custom components via stub files:
- Stub files are in `stubs/` directory
- Customize for your team's needs
- Version control with your project

---

## Troubleshooting

### Command Not Found

Make sure the command is registered:
```bash
php artisan list | grep gsm
```

Should show:
```
  gsm:add         Download a component from the marketplace
  gsm:component   Create a new component across all stacks
```

### Authentication Error

Check your token:
```bash
php artisan gsm:add component --token=your_token
```

Generate new token at: `/dashboard/api`

### Permission Denied

For premium components:
1. Subscribe to Pro plan
2. Or purchase individual component
3. Then retry download

### Component Not Found

Search available components:
- Visit `/components` in your browser
- Check marketplace for available items

### File Already Exists

Use `--force` to overwrite:
```bash
php artisan gsm:component Button --force
```

Or rename your component:
```bash
php artisan gsm:component PrimaryButton
```

---

## Advanced Usage

### Custom Categories

Create custom categories by modifying:
- `app/Console/Commands/GsmComponentCommand.php`
- Add your category to the `$categories` array

### Custom Templates

Modify stub files:
- `stubs/*.stub`
- Customize for your team's patterns

### Pre/Post Hooks

Add custom logic in:
- `app/Console/Commands/GsmComponentCommand.php`
- Override `create*Component()` methods

### CI/CD Integration

In your deployment script:
```bash
#!/bin/bash
php artisan gsm:add button
php artisan gsm:add card
php artisan gsm:add input
php artisan migrate --force
```

---

## Best Practices

1. **Use Descriptive Names**
   ```bash
   php artisan gsm:component UserProfileCard
   ```

2. **Choose Correct Category**
   ```bash
   php artisan gsm:component SearchInput --category=forms
   ```

3. **Maintain Consistency**
   ```bash
   php artisan gsm:component PrimaryAction --variant=primary --size=md
   ```

4. **Document Custom Components**
   - Edit generated `.md` files
   - Add examples and use cases

5. **Version Control**
   - Commit generated components
   - Track changes in stubs

---

## Support

For issues or questions:
- Check documentation at `/docs`
- Visit community forum
- Contact support team

---

## Examples Gallery

### Form Components
```bash
php artisan gsm:component TextInput --category=forms
php artisan gsm:component SelectInput --category=forms
php artisan gsm:component DatePicker --category=forms
```

### Feedback Components
```bash
php artisan gsm:component SuccessToast --category=feedback --variant=primary
php artisan gsm:component ErrorAlert --category=feedback --variant=danger
```

### Navigation Components
```bash
php artisan gsm:component SidebarMenu --category=navigation
php artisan gsm:component Breadcrumb --category=navigation
```

### Data Display
```bash
php artisan gsm:component StatsCard --category=data-display --variant=primary
php artisan gsm:component DataTable --category=data-display
```

---

## 🎉 Start Building!

Create your first component now:

```bash
php artisan gsm:component MyButton --variant=primary --size=md
```

Then use it across all frameworks!

```blade
<!-- Blade -->
<x-components.blade.utilities.my-button label="Click Me" />

<!-- Livewire -->
<livewire:MyButton label="Click Me" />

<!-- React -->
<MyButton label="Click Me" />

<!-- Vue -->
<MyButton label="Click Me" />
```

Happy coding! 🚀
