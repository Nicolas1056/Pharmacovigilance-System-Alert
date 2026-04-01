import { ref } from 'vue';
import axios from 'axios';

export function useAlerts() {
    const showModal = ref(false);
    const isSending = ref(false);
    const isSendingBulk = ref(false);
    const selectOrder = ref(null);

    const openModal = (order) => {
        selectOrder.value = order;
        showModal.value = true;
    }

    const closeModal = () => {
        showModal.value = false;
        selectOrder.value = null;
    }

    const sendAlertEmail = async () => {
        if (!selectOrder.value) return;

        isSending.value = true;
        try {
            await axios.post('/api/alerts/send', {
                order_id: selectOrder.value.id,
                customer_id: selectOrder.value.customer_id
            });
            alert("¡Correo de urgencia enviado exitosamente!" + selectOrder.value.customer.name);
            closeModal();
        } catch (error) {
            console.error(error);
            alert("Error del servidor enviando el correo.");
        } finally {
            isSending.value = false;
        }
    };

    const sendBulkAlerts = async (idsArray) => {
        if (!idsArray || idsArray.length === 0) return;
        
        isSendingBulk.value = true;
        try {
            await axios.post('/api/alerts/send', {
                bulk: true,
                order_ids: idsArray
            });
            alert(`¡Emergencia: Alerta masiva enviada a los ${idsArray.length} clientes afectados con éxito!`);
        } catch (error) {
            console.error(error);
            alert("Error enviando alerta masiva.");
        } finally {
            isSendingBulk.value = false;
        }
    };

    return {
        showModal,
        isSending,
        isSendingBulk,
        openModal,
        closeModal,
        sendAlertEmail,
        sendBulkAlerts
    };
}