<script setup>
import { computed, useAttrs } from 'vue';

defineOptions({
  inheritAttrs: false // Para que el CSS no se inyecte doble
});

const props = defineProps({
  modelValue: { type: [String, Number], default: '' },
  label: { type: String, default: '' },
  type: { type: String, default: 'text' },
  placeholder: { type: String, default: '' },
  required: { type: Boolean, default: false },
  inputClass: { type: String, default: '' } 
});

defineEmits(['update:modelValue']);

// Excluye las clases genéricas al bindeo interno
const attrs = useAttrs();
const filteredAttrs = computed(() => {
    const obj = { ...attrs };
    delete obj.class;
    return obj;
});
</script>

<template>
  <div class="flex flex-col gap-1 w-full text-left" :class="$attrs.class">
    <!-- Etiqueta opcional parametrizada -->
    <label v-if="label" class="text-sm font-bold text-gray-800">{{ label }}</label>
    <div class="relative flex items-center gap-2">
      <!-- Prefijo para colocar textos como 'Lot Number:' dentro si se desea -->
      <slot name="prefix"></slot>
      
      <input
        v-bind="filteredAttrs"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        :type="type"
        :required="required"
        :placeholder="placeholder"
        :class="[
            'w-full border border-gray-300 bg-white px-3 py-1.5 text-sm focus:outline-none focus:border-[#2a3f5a] transition-colors shadow-sm', 
            inputClass
        ]"
      />
    </div>
  </div>
</template>
