<script setup>
import BaseButton from './ui/BaseButton.vue';

defineProps({
    show: Boolean,
    isSending: Boolean
});
defineEmits(['close', 'send']);
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('close')"></div>
    
    <div class="relative bg-white shadow-xl w-full max-w-sm border border-gray-300">
      
      <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200">
        <h3 class="text-[#2a3f5a] font-bold text-sm tracking-wide">Send Alert to Customer</h3>
        <button @click="$emit('close')" class="text-gray-500 hover:text-black">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
      </div>

      <div class="px-6 py-8 text-sm text-gray-800 tracking-tight leading-relaxed">
        Warning: Important recall notice for medication Lot #951357. Please contact the pharmacy immediately.
      </div>

      <div class="px-3 py-3 flex items-center justify-end gap-2 bg-[#f0f3f6] border-t border-gray-300">
        <BaseButton 
          variant="success" 
          size="sm"
          :loading="isSending"
          @click="$emit('send')"
          text="Send Email"
        />
        <BaseButton 
          variant="primary" 
          size="sm"
          :disabled="isSending"
          @click="$emit('close')"
          text="Cancel"
        />
      </div>

    </div>
  </div>
</template>
