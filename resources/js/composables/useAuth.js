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
            await axios.get('/sanctum/csrf-cookie').catch(e => { console.warn('CSRF warning:', e) });
            const response = await axios.post('/api/login', { username, password });
            const token = response.data.token;

            // Guardamos la sesión
            localStorage.setItem('auth_token', token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            // Redirigir si tiene éxito
            await router.push('/dashboard');

        } catch (error) {
            console.error('Error during login:', error);
            if (error.response && error.response.status === 401) {
                errorMessage.value = 'Usuario o contraseña incorrectos';
            } else {
                errorMessage.value = error.response?.data?.message || 'Error al conectar con el servidor';
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
