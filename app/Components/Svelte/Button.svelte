
<script lang="ts">
  export let label: string = 'Button';
  export let variant: 'primary' | 'secondary' | 'ghost' | 'danger' | 'success' = 'primary';
  export let size: 'sm' | 'md' | 'lg' | 'xl' = 'md';
  export let disabled: boolean = false;
  export let loading: boolean = false;
  
  const variants = {
    primary: 'bg-electric-blue text-deep-space hover:bg-blue-400',
    secondary: 'bg-toxic-green text-deep-space hover:bg-green-400',
    ghost: 'bg-transparent text-indigo hover:bg-indigo-100',
    danger: 'bg-red-500 text-white hover:bg-red-600',
    success: 'bg-green-500 text-white hover:bg-green-600'
  };
  
  const sizes = {
    sm: 'text-sm px-3 py-1.5',
    md: 'text-base px-4 py-2',
    lg: 'text-lg px-6 py-3',
    xl: 'text-xl px-8 py-4'
  };
  
  let className = '';
  export { className as class };
</script>

<button
  class="gsm-btn gsm-btn-{variant} gsm-btn-{size} font-medium rounded-lg transition-all duration-200 border-2 {variants[variant]} {sizes[size]} {className}"
  disabled={disabled || loading}
  on:click
>
  {#if loading}
    <span class="flex items-center gap-2">
      <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      {label}
    </span>
  {:else}
    {label}
  {/if}
</button>

<style>
  .gsm-btn {
    @apply font-medium rounded-lg transition-all duration-200 border-2;
  }
  
  .gsm-btn:disabled {
    @apply opacity-50 cursor-not-allowed;
  }
</style>
