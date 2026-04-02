<script setup>
const props = defineProps({
  columns: { type: Array, required: true },
  items: { type: Array, required: true },
  loading: { type: Boolean, default: false },
  pagination: { type: Object, default: () => ({}) },
  emptyText: { type: String, default: 'Sin datos que reportar.' }
});

const emit = defineEmits(['changePage']);

// Resuelve propiedades anidadas desde un string, ej: 'customer.name' en un JSON { customer: { name: 'Juan' } }
const formatValue = (item, path) => {
  if (!path) return '';
  return path.split('.').reduce((o, i) => o ? o[i] : '', item);
};
</script>

<template>
  <div class="overflow-x-auto border border-gray-300 bg-white">
    <table class="w-full text-left text-sm whitespace-nowrap">
      
      <!-- Rendereo Dinámico de Cabeceras basado en el Array columns -->
      <thead class="bg-[#2a3f5a] text-white">
        <tr>
          <th 
            v-for="(col, index) in columns" 
            :key="col.key" 
            class="px-4 py-2 font-medium border-x border-[#1f2f44]"
            :class="col.align === 'center' ? 'text-center' : 'text-left'"
          >
            {{ col.label }}
          </th>
        </tr>
      </thead>
      
      <!-- Rendereo del Cuerpo de la tabla (Estados incl) -->
      <tbody class="divide-y divide-gray-200">
        
        <!-- Estado de Loading -->
        <tr v-if="loading">
          <td :colspan="columns.length" class="px-4 py-8 text-center text-gray-500 bg-gray-50">
            Buscando datos en el sistema...
          </td>
        </tr>
        
        <!-- Estado Vacío -->
        <tr v-else-if="items.length === 0">
          <td :colspan="columns.length" class="px-4 py-8 text-center text-gray-400 bg-gray-50">
            {{ emptyText }}
          </td>
        </tr>

        <!-- Datos -->
        <tr v-for="(item, index) in items" :key="item.id || index" :class="index % 2 === 0 ? 'bg-white' : 'bg-[#f8f9fa]'">
          
          <!-- Columnas dinámicas -->
          <td 
            v-for="col in columns" 
            :key="col.key" 
            class="px-4 py-2 border-r border-gray-200"
            :class="col.align === 'center' ? 'text-center' : 'text-left'"
          >
            <!-- 
                Slot por defecto: Si el padre quiere pintar botones, usa <template #cell(actions)="{ item }"> 
            -->
            <slot :name="`cell(${col.key})`" :item="item" :index="index">
               {{ formatValue(item, col.key) }}
            </slot>
          </td>
          
        </tr>
      </tbody>
    </table>

    <!-- Footer de Paginación -->
    <div class="px-4 py-3 bg-gray-50 border-t border-gray-300 flex items-center justify-between">
      <div class="text-xs text-gray-500">
        Mostrando {{ items.length }} registros de {{ pagination?.total || 0 }}
      </div>
      <div class="flex gap-2">
        <button 
          class="px-3 py-1 bg-white border border-gray-300 text-xs font-semibold rounded hover:bg-gray-100 disabled:opacity-50 transition-colors"
          :disabled="pagination?.currentPage === 1 || loading"
          @click="emit('changePage', pagination.currentPage - 1)"
        >
          Anterior
        </button>
        <span class="text-xs font-bold self-center px-2">
          Página {{ (pagination?.currentPage) || 1 }} de {{ (pagination?.lastPage) || 1 }}
        </span>
        <button 
          class="px-3 py-1 bg-white border border-gray-300 text-xs font-semibold rounded hover:bg-gray-100 disabled:opacity-50 transition-colors"
          :disabled="pagination?.currentPage === pagination?.lastPage || loading"
          @click="emit('changePage', pagination.currentPage + 1)"
        >
          Siguiente
        </button>
      </div>
    </div>
  </div>
</template>
