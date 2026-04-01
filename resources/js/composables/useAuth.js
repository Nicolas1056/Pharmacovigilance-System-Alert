import { ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from 'axios';

export function useAuth() {
    const router = useRouter();
    const isLoading = ref(false);
    const errorMessage = ref('');

    const login = async (username, password) => {
        isLoading.value = true;
        errorMessage.value = '';

        try {
            const response = await axios.post('/api/login', { username, password });
            const token = response.data.token;

            // Guardamos la sesión
            localStorage.setItem('auth_token', token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            // Redirigir si tiene éxito
            router.push('/dashboard');

        } catch (error) {
            // Manejo de errores 
            if (error.response && error.response.status === 401) {
                errorMessage.value = 'Credenciales Inválidas';
            } else {
                errorMessage.value = 'Error al contactar la API';
            }
        } finally {
            isLoading.value = false;
        }
    };

    return {
        login,
        isLoading,
        errorMessage
    };
}
