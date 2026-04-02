import { createRouter, createWebHistory } from 'vue-router';
import Login from './views/Login.vue';
import Dashboard from './views/Dashboard.vue';

const routes = [
    { path: '/', redirect: '/login' },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { guestOnly: true }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/dashboard'
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation Guards
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('auth_token');
    
    if (to.meta.requiresAuth && !token) {
        // Redirigir al login si no está autenticado
        next('/login');
    } else if (to.meta.guestOnly && token) {
        // Prevenir que vuelva al login si ya inició sesión
        next('/dashboard');
    } else {
        next();
    }
});

export default router;
