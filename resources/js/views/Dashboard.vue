<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import BaseInput from '../components/ui/BaseInput.vue';
import BaseButton from '../components/ui/BaseButton.vue';
import DataTable from '../components/ui/DataTable.vue';
import AlertModal from '../components/AlertModal.vue';
import OrderModal from '../components/orderModal.vue';
import CustomerModal from '../components/customerModal.vue';
import { useOrders } from '../composables/useOrders';
import { useAlerts } from '../composables/useAlerts';

const { loading, orders, filters, paginationData, fetchOrders } = useOrders();
const { showModal, isSending, isSendingBulk, openModal, closeModal, sendAlertEmail, sendBulkAlerts, selectOrder } = useAlerts();
const router = useRouter();
const showOrderView = ref(false);
const showCustomerView = ref(false);
const inspectingOrder = ref(null);
const selectedOrders = ref([]); // Control de checkboxes

// Si la tabla de ordenes cambia (nueva búsqueda), limpiamos la selección
watch(orders, () => {
  selectedOrders.value = [];
});

// Configuración de las columnas de la tabla
const tableColumns = [
  { key: 'checkbox', label: '☑', align: 'center' },
  { key: 'id', label: 'Order ID', align: 'left' },
  { key: 'customer.name', label: 'Customer', align: 'left' },
  { key: 'purchase_date', label: 'Date', align: 'left' },
  { key: 'actions', label: 'Actions', align: 'center' }
];

const formatDate = (dateString) => {
  const d = new Date(dateString);
  return d.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' });
};

const logout = () => {
    localStorage.removeItem('auth_token');
    router.push('/login');
};

onMounted(() => {
    fetchOrders();
});
</script>

<template>
  <div class="min-h-screen bg-[#e9ecef]">
    <header class="bg-[#2a3f5a] text-white flex items-center justify-between px-4 py-2 border-b-4 border-[#1f2f44]">
      <div class="flex items-center gap-3">
        <span class="text-sm font-semibold tracking-wider ml-2">Order Search</span>
      </div>
      <!-- Right actions -->
      <div class="flex items-center gap-4">
        <button @click="logout" title="Logout" class="hover:text-red-400">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        </button>
      </div>
    </header>

    <main class="p-6">
      <div class="bg-white border border-gray-300 shadow-sm max-w-5xl mx-auto">
        <div class="px-4 py-4 border-b border-gray-200 bg-[#f8f9fa]">
          <form @submit.prevent="fetchOrders" class="flex flex-col md:flex-row items-center gap-6">
              
              <BaseInput 
                v-model="filters.lot" 
                inputClass="w-48"
              >
                <template #prefix><span class="text-sm font-bold text-gray-800 whitespace-nowrap">Lot Number :</span></template>
              </BaseInput>

              <div class="flex items-center gap-2 ml-auto">
                 <span class="text-sm text-gray-600 whitespace-nowrap">Start Date:</span>
                 <BaseInput v-model="filters.start_date" type="date" />
              </div>
              <div class="flex items-center gap-2">
                 <span class="text-sm text-gray-600 whitespace-nowrap">End Date:</span>
                 <BaseInput v-model="filters.end_date" type="date" />
              </div>
            </form>
          </div>

          <div>
            <div class="bg-[#f0f3f6] px-4 py-2 flex items-center justify-between border-b border-gray-300">
              <h2 class="text-sm font-bold text-[#4a5f7a]">Order Results</h2>
              <div class="flex gap-3">
                 <!-- Botón para envío masivo de alertas -->
                <BaseButton 
                   variant="action" 
                   class="bg-red-800! hover:bg-red-900! border-red-900! border"
                   text="Bulk Send Alert ⚠️"
                   :loading="isSendingBulk"
                   :disabled="selectedOrders.length === 0"
                   @click="sendBulkAlerts(selectedOrders, filters.lot)"
                />
                
                <BaseButton 
                   variant="success" 
                   text="Search" 
                   :disabled="!filters.lot"
                   @click="fetchOrders"
                />
              </div>
            </div>

            <!-- Componente Tabla Dinámica Base -->
            <DataTable 
               :columns="tableColumns"
               :items="orders"
               :loading="loading"
               :pagination="paginationData"
               @changePage="fetchOrders"
            >
               <!-- Checkbox para seleccionar órdenes -->
               <template #cell(checkbox)="{ item }">
                  <input 
                    type="checkbox" 
                    :value="item.id" 
                    v-model="selectedOrders" 
                    class="w-4 h-4 cursor-pointer text-[#2a3f5a] focus:ring-[#2a3f5a] border-gray-300 rounded" 
                  />
               </template>

               <!-- Formateamos la fecha -->
               <template #cell(purchase_date)="{ item }">
                   {{ formatDate(item.purchase_date) }}
               </template>

               <!-- Botones de acción (Ver orden, alertar, etc) -->
               <template #cell(actions)="{ item }">
                 <div class="flex items-center justify-center gap-2">
                   <BaseButton variant="action" size="xs" text="View Order" @click="inspectingOrder=item; showOrderView=true">
                     <template #icon><span>👁️</span></template>
                   </BaseButton>
                   
                   <BaseButton variant="primary" size="xs" text="Alert Buyer" @click="openModal(item)">
                     <template #icon><span class="text-yellow-500">⭐</span></template>
                   </BaseButton>
                   
                   <BaseButton variant="action" size="xs" text="View Buyer" @click="inspectingOrder=item; showCustomerView=true">
                     <template #icon><span>👤</span></template>
                   </BaseButton>
                 </div>
               </template>
            </DataTable>
          </div>
        </div>
      </main>
    </div>

  <AlertModal
    :show="showModal"
    :isSending="isSending"
    :lotNumber="filters.lot || selectOrder?.items[0]?.medication.lot_number || 'N/A'"
    @close="closeModal"
    @send="sendAlertEmail(filters.lot)"
  />
  <OrderModal
    :show="showOrderView"
    :order="inspectingOrder"
    @close="showOrderView=false"
  />
  <CustomerModal
    :show="showCustomerView"
    :customer="inspectingOrder ? inspectingOrder.customer : null" 
    @close="showCustomerView=false"
  />
</template>
