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

    const sendAlertEmail = async (lotNumber = null) => {
        if (!selectOrder.value) return;

        isSending.value = true;
        try {
            const response = await axios.post('/api/alerts/send', {
                order_id: selectOrder.value.id,
                customer_id: selectOrder.value.customer_id,
                lot_number: lotNumber
            });
            alert(response.data.message || "¡Correo de urgencia enviado exitosamente a " + selectOrder.value.customer.name + "!");
            closeModal();
        } catch (error) {
            console.error(error);
            if (error.response && error.response.status === 422) {
                alert("Atención: " + error.response.data.message);
            } else {
                alert("Error del servidor enviando el correo.");
            }
        } finally {
            isSending.value = false;
        }
    };

    const sendBulkAlerts = async (idsArray, lotNumber = null) => {
        if (!idsArray || idsArray.length === 0) return;
        
        isSendingBulk.value = true;
        try {
            const response = await axios.post('/api/alerts/send', {
                bulk: true,
                order_ids: idsArray,
                lot_number: lotNumber
            });
            alert(response.data.message || `¡Emergencia: Alerta masiva enviada con éxito!`);
        } catch (error) {
            console.error(error);
            if (error.response && error.response.status === 422) {
                alert("Atención: " + error.response.data.message);
            } else {
                alert("Error enviando alerta masiva.");
            }
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
        sendBulkAlerts,
        selectOrder
    };
}