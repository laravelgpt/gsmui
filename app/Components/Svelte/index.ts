
export { default as Button } from './Button.svelte';

// Types
export interface ComponentProps {
  label?: string;
  variant?: 'primary' | 'secondary' | 'ghost' | 'danger' | 'success';
  size?: 'sm' | 'md' | 'lg' | 'xl';
  disabled?: boolean;
  loading?: boolean;
  class?: string;
}

// Composables
export function useComponent() {
  return {
    // Component utilities
  };
}
