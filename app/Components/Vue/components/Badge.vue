<template>
  <div
    :class="\`Utilities-badge theme-${theme} size-${size} variant-${variant}${disabled ? ' disabled' : ''}\`"
  >
    <div v-if="title" class="font-bold mb-2">{{ title }}</div>
    <div v-if="content" class="content">{{ content }}</div>
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'Badge',
  props: {
    title: { type: String, default: null },
    content: { type: [String, Object], default: null },
    variant: { type: String, default: 'base' },
    size: { type: String, default: 'md' },
    theme: { type: String, default: 'default' },
    disabled: { type: Boolean, default: false },
  },
};
</script>
