import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

export function useOrders() {
    const router = useRouter();

    const loading = ref(false);
    const orders = ref([]);
    const currentPage = ref(1);

    const filters = reactive({
        lot: '',
        start_date: '',
        end_date: ''
    });

    const ejectUser = () => {
        localStorage.removeItem('auth_token');
        delete axios.defaults.headers.common['Authorization'];
        router.push('/login');
    };

    const fetchOrders = async () => {
        loading.value = true;
        try {
            const response = await axios.get('/api/medications/search', {
                params: {
                    lot: filters.lot,
                    start_date: filters.start_date || null,
                    end_date: filters.end_date || null,
                }
            });
            orders.value = response.data.data;
            currentPage.value = response.data.current_page;
        } catch (error) {
            if (error.response?.status === 401) {
                alert("Tu sesión ha expirado.");
                ejectUser();
            } else {
                console.error("Error obteniendo resultados:", error);
                alert("Hubo un error contactando la base de datos.");
            }
        } finally {
            loading.value = false;
        }
    };

    return {
        loading,
        orders,
        currentPage,
        filters,
        ejectUser,
        fetchOrders
    };
}
