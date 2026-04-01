<script setup>
import BaseButton from './ui/BaseButton.vue';

defineProps({
    show: Boolean,
    order: Object
});
defineEmits(['close']);
</script>

<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="$emit('close')"></div>
    
    <div class="relative bg-white shadow-xl w-full max-w-lg border border-gray-300">
      <div class="bg-[#2a3f5a] text-white px-4 py-3">
        <h3 class="font-bold text-sm tracking-wide">Order Details</h3>
      </div>

      <div v-if="order" class="p-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
          <div>
            <span class="block text-xs text-gray-500 uppercase tracking-wider font-bold">Order ID</span>
            <span class="text-sm text-gray-800 font-medium">{{ order.id.toString().padStart(7, '0') }}</span>
          </div>
          <div>
            <span class="block text-xs text-gray-500 uppercase tracking-wider font-bold">Purchase Date</span>
            <span class="text-sm text-gray-800 font-medium">{{ new Date(order.purchase_date).toLocaleDateString('en-US') }}</span>
          </div>
        </div>

        <h4 class="text-sm font-bold text-gray-800 mb-2 border-b border-gray-200 pb-1">Medications Included</h4>
        <ul class="divide-y divide-gray-100">
          <li v-for="item in order.items" :key="item.id" class="py-2 flex justify-between">
            <span class="text-sm text-gray-700 font-medium">{{ item.medication.name }}</span>
            <span class="text-xs font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-600 border border-gray-200">Lot: {{ item.medication.lot_number }}</span>
          </li>
        </ul>
      </div>

      <!-- Pie del modal usando nuestro BaseButton -->
      <div class="px-4 py-3 bg-[#f0f3f6] border-t border-gray-300 flex justify-end">
        <BaseButton variant="primary" text="Close Window" @click="$emit('close')" />
      </div>
    </div>
  </div>
</template>
