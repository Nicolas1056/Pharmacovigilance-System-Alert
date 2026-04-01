<script setup>
import BaseButton from './ui/BaseButton.vue';

defineProps({
    show: Boolean,
    customer: Object
});
defineEmits(['close']);
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('close')"></div>
    
    <div class="relative bg-white shadow-xl w-full max-w-sm border border-gray-300">
      <div class="bg-[#2E7D65] text-white px-4 py-3">
        <h3 class="font-bold text-sm tracking-wide">Buyer Information</h3>
      </div>

      <div v-if="customer" class="p-6 space-y-4">
        <div>
          <span class="block text-xs text-gray-500 uppercase tracking-wider font-bold">Full Name</span>
          <span class="text-sm text-gray-800 font-medium capitalize">{{ customer.name }}</span>
        </div>
        <div>
          <span class="block text-xs text-gray-500 uppercase tracking-wider font-bold">Email Address</span>
          <a :href="'mailto:' + customer.email" class="text-sm text-blue-600 hover:underline font-medium">{{ customer.email }}</a>
        </div>
      </div>

      <!-- Pie del modal usando nuestro BaseButton -->
      <div class="px-4 py-3 bg-[#f0f3f6] border-t border-gray-300 flex justify-end">
        <BaseButton variant="primary" text="Close Window" @click="$emit('close')" />
      </div>
    </div>
  </div>
</template>
