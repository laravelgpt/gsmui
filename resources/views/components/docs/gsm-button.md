
# GSM Button Component

The `gsm-button` is the foundational interactive element in the GSM-UI library. Built with Livewire Volt and styled with the Midnight Electric theme, it provides three visual variants, smooth loading states, and full accessibility support.

## Features

- **Three Visual Variants**: Primary (glowing blue), Danger (glowing red), Ghost (glassmorphism)
- **Loading States**: Integrated spinner animation with `wire:loading`
- **Icon Support**: Flexible icon placement (left/right)
- **Size Options**: Small, medium, and large variants
- **Full Width**: Optional full-width display
- **100% Accessible**: Keyboard navigation and focus indicators

## Quick Start

```blade
<x-components.volt.gsm-button label="Click Me" />
```

## Preview

### Full Usage Example

```blade
<gsm-button
 label="Delete Account"
 variant="danger"
 size="sm"
/>
```

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Delete Account\" variant=\"danger\" size=\"sm\" />\`">
    <x-components.volt.gsm-button label="Delete Account" variant="danger" size="sm" />
</x-components.docs-wrapper>

### Primary Variant

The primary button uses a glowing electric blue effect with a subtle shine animation on hover.

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Primary Large\" variant=\"primary\" size=\"lg\" />\`">
    <x-components.volt.gsm-button label="Primary Large" variant="primary" size="lg" />
</x-components.docs-wrapper>

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Primary Small\" variant=\"primary\" size=\"sm\" />\`">
    <x-components.volt.gsm-button label="Primary Small" variant="primary" size="sm" />
</x-components.docs-wrapper>

### Danger Variant

The danger button features a glowing red appearance, perfect for destructive actions.

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Delete Item\" variant=\"danger\" />\`">
    <x-components.volt.gsm-button label="Delete Item" variant="danger" />
</x-components.docs-wrapper>

### Ghost Variant

The ghost button uses glassmorphism styling for a subtle, transparent appearance.

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Ghost Button\" variant=\"ghost\" />\`">
    <x-components.volt.gsm-button label="Ghost Button" variant="ghost" />
</x-components.docs-wrapper>

## With Icons

Buttons can include SVG icons on either side of the label.

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Download\" variant=\"primary\" :icon=\"\\\"<svg class=\\\"w-4 h-4\\\" viewBox=\\\"0 0 24 24\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" stroke-width=\\\"2\\\"><path d=\\\"M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4\\\"></path><polyline points=\\\"7 10 12 15 17 10\\\"></polyline><line x1=\\\"12\\\" y1=\\\"15\\\" x2=\\\"12\\\" y2=\\\"3\\\"></line></svg>\\\"\" icon-position=\"left\" />\`">
    <x-components.volt.gsm-button label="Download" variant="primary" :icon="\"<svg class='w-4 h-4' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'><path d='M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4'></path><polyline points='7 10 12 15 17 10'></polyline><line x1='12' y1='15' x2='12' y2='3'></line></svg>\" icon-position=\"left\" />
</x-components.docs-wrapper>

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Next\" variant=\"primary\" :icon=\"\\\"<svg class=\\\"w-4 h-4\\\" viewBox=\\\"0 0 24 24\" fill=\\\"none\\\" stroke=\\\"currentColor\\\" stroke-width=\\\"2\\\"><polyline points=\\\"9 18 15 12 9 6\\\"></polyline></svg>\\\"\" icon-position=\"right\" />\`">
    <x-components.volt.gsm-button label="Next" variant="primary" :icon="\"<svg class='w-4 h-4' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2'><polyline points='9 18 15 12 9 6'></polyline></svg>\" icon-position=\"right\" />
</x-components.docs-wrapper>

## Loading State

When the `loading` prop is set to `true`, the button displays a spinner animation and changes the label to "Processing...".

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Submit\" variant=\"primary\" loading=\"true\" />\`">
    <x-components.volt.gsm-button label="Submit" variant="primary" loading="true" />
</x-components.docs-wrapper>

### Disabled State

Disabled buttons appear semi-transparent and cannot be clicked.

<x-components.docs-wrapper :code="\`<x-components.volt.gsm-button label=\"Disabled\" variant=\"primary\" disabled=\"true\" />\`">
    <x-components.volt.gsm-button label="Disabled" variant="primary" disabled="true" />
</x-components.docs-wrapper>

## Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `label` | String | `"Button"` | The text displayed on the button |
| `variant` | String | `"primary"` | The visual style: `primary`, `danger`, or `ghost` |
| `size` | String | `"md"` | The size: `sm`, `md`, or `lg` |
| `disabled` | Boolean | `false` | Whether the button is disabled |
| `loading` | Boolean | `false` | Whether to show the loading spinner |
| `type` | String | `"button"` | HTML button type: `button`, `submit`, or `reset` |
| `fullWidth` | Boolean | `false` | Whether to expand to full container width |
| `icon` | String | `null` | HTML string for an SVG icon to display |
| `iconPosition` | String | `"left"` | Icon position: `left` or `right` |
| `className` | String | `""` | Additional CSS classes to apply |

## Usage Examples

### Basic Button

```blade
<x-components.volt.gsm-button label="Click Me" />
```

### Primary Action

```blade
<x-components.volt.gsm-button 
    label="Save Changes" 
    variant="primary" 
    size="lg"
    :icon="\"<svg>...</svg>\"" 
    icon-position=\"left\" 
/>
```

### Submit Form

```blade
<x-components.volt.gsm-button 
    label="Submit" 
    type="submit" 
    variant="primary" 
    loading="{{ $isProcessing }}" 
/>
```

### **Delete Account (Danger)**

```blade
<gsm-button
 label="Delete Account"
 variant="danger"
 size="sm"
/>
```

### Ghost Navigation

```blade
<x-components.volt.gsm-button 
    label="Back" 
    variant="ghost" 
    :icon="\"<svg>...</svg>\"" 
    icon-position=\"left\" 
/>
```

### Full Width Button

```blade
<x-components.volt.gsm-button 
    label="Continue" 
    variant="primary" 
    full-width="true" 
/>
```

## Accessibility

The `gsm-button` component includes comprehensive accessibility features:

- **Keyboard Navigation**: Fully operable with Tab, Space, and Enter keys
- **Focus Indicators**: Visible focus ring with electric blue outline
- **Screen Reader Support**: Proper ARIA labels when using icons without text
- **Loading States**: Announces loading status to assistive technologies
- **Disabled States**: Prevents interaction and announces disabled state

## Animations

All animations respect the `prefers-reduced-motion` media query for users who prefer less motion.

## Theme Integration

The button uses CSS variables from the Midnight Electric theme:

```css
--electric-blue: #00D4FF
--toxic-green: #39FF14  
--indigo: #6366F1
--deep-space: #0B0F19
```

## Best Practices

1. **Use Primary for Main Actions**: Reserve the glowing primary style for the most important call-to-action
2. **Danger for Destructive Actions**: Use the red variant for delete, remove, or dangerous operations
3. **Ghost for Secondary Actions**: Ideal for cancel, back, or low-priority actions
4. **Consistent Sizing**: Use the same size for buttons within the same context
5. **Icon + Label**: Always pair icons with text labels for clarity
6. **Loading Feedback**: Show the loading state for any action that takes time

## Related Components

- [Form](/components/form) - For button groups in forms
- [Icon](/components/icon) - For available icon variations
- [Loading](/components/loading) - For loading indicators

## API Reference

### Events

The component emits standard button events that can be captured with Alpine.js or Livewire:

```blade
<button 
    x-on:click="handleClick"
    wire:click="submitForm"
/>
```

### Styling Overrides

Custom styles can be applied using the `className` prop:

```blade
<x-components.volt.gsm-button 
    label="Custom" 
    class-name="bg-gradient-to-r from-pink-500 to-orange-500"
/>
```
