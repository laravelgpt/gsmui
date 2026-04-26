
# GSM-UI Component Generator - Agent Update

## System Role
Expert TALL Stack Developer specializing in the GSM-UI Component Generator system.

## Core Architecture

### 1. Copy Code Wrapper (resources/views/components/docs-wrapper.blade.php)
- Alpine.js state management for tab switching
- Copy-to-clipboard functionality with 2-second reset
- Syntax-highlighted code display
- Preview and Code tabs

### 2. Icon System (resources/views/components/icons/)
- SVG icon components
- Consistent sizing and styling
- Current color support

### 3. Livewire Volt Components (resources/views/components/volt/)
- Functional reactive components
- Prop-driven configuration
- Built-in loading states
- Midnight Electric theme integration

### 4. Documentation System (resources/views/components/docs/)
- Markdown-based documentation
- Live code examples
- Interactive demos

### 5. CLI Stub System (stubs/)
- Publishable component templates
- php artisan gsm:add integration
- User-local file generation

## Generation Rules

### For Each Component Request:
1. ✅ Create Volt component (resources/views/components/volt/{name}.blade.php)
2. ✅ Create documentation (resources/views/components/docs/{name}.md)
3. ✅ Create stub file (stubs/{name}.stub)

### Code Standards:
- CSS variables for colors (no hardcoded values)
- Shared Blade components for repetitive elements
- Blade::render() for stateless UI pieces
- Livewire for reactive logic only

## First Component: gsm-button

**Status: ✅ COMPLETE**

### Features:
- 3 variants (primary, danger, ghost)
- 3 sizes (sm, md, lg)
- Icon support (left/right)
- Loading states with spinner
- Full width option
- 100% accessible
- Midnight Electric theme

### Files Created:
1. ✅ Volt Component: resources/views/components/volt/gsm-button.blade.php
2. ✅ Documentation: resources/views/components/docs/gsm-button.md
3. ✅ Stub: stubs/gsm-button.stub
4. ✅ Icon: resources/views/components/icons/copy.blade.php
5. ✅ Wrapper: resources/views/components/docs-wrapper.blade.php

## Ready for Next Component

Awaiting component generation request following GSM-UI specifications.
