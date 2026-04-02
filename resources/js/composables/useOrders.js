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

    const paginationData = ref({});

    const ejectUser = () => {
        localStorage.removeItem('auth_token');
        delete axios.defaults.headers.common['Authorization'];
        router.push('/login');
    };

    const fetchOrders = async (page = 1) => {
        loading.value = true;
        currentPage.value = page;
        try {
            const response = await axios.get('/api/medications/search', {
                params: {
                    page: page,
                    lot: filters.lot,
                    start_date: filters.start_date || null,
                    end_date: filters.end_date || null,
                }
            });
            orders.value = response.data.data;
            paginationData.value = {
                currentPage: response.data.current_page,
                lastPage: response.data.last_page,
                total: response.data.total
            }
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
        paginationData,
        filters,
        ejectUser,
        fetchOrders
    };
}
