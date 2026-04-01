<script setup>
const props = defineProps({
  type: { type: String, default: 'button' },
  variant: { type: String, default: 'primary' },
  size: { type: String, default: 'md' },
  disabled: { type: Boolean, default: false },
  loading: { type: Boolean, default: false },
  text: { type: String, default: '' },
});

defineEmits(['click']);

const variantClasses = {
  primary: 'bg-[#2a3f5a] text-white hover:bg-[#1a2636] border border-[#1a2636]',
  success: 'bg-[#2E7D65] text-white hover:bg-[#235e4c] border border-[#235e4c] shadow-[inset_0_1px_0_rgba(255,255,255,0.2)]',
  action: 'bg-[#4a5f7a] text-white hover:bg-[#38485c] border border-[#38485c]'
};

const sizeClasses = {
  xs: 'px-3 py-1 text-xs',
  sm: 'px-4 py-1.5 text-xs',
  md: 'px-4 py-2 text-sm',
  lg: 'px-6 py-2.5 text-base w-full',
};
</script>

<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="[
      'inline-flex items-center justify-center gap-1.5 font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1',
      sizeClasses[size],
      variantClasses[variant],
      (disabled || loading) ? 'opacity-50 cursor-not-allowed' : ''
    ]"
    @click="$emit('click')"
  >
    <!-- Spinner dinámico de carga -->
    <span v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4">
      <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </span>
    <!-- Slot para íconos u otros elementos extras antes del texto -->
    <slot name="icon" v-if="!loading"></slot>
    
    <!-- El propio texto -->
    <slot>{{ text }}</slot>
  </button>
</template>
