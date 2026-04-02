import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/Login.vue'
import Dashboard from '../components/Dashboard.vue'
import OrderDetail from '../components/OrderDetail.vue'
import CustomerDetail from '../components/CustomerDetail.vue'

const routes = [
    {
        path: '/',
        name: 'Login',
        component: Login
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/orders/:id',
        name: 'OrderDetail',
        component: OrderDetail,
        meta: { requiresAuth: true }
    },
    {
        path: '/customers/:id',
        name: 'CustomerDetail',
        component: CustomerDetail,
        meta: { requiresAuth: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guard for authentication
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')
    
    if (to.meta.requiresAuth && !token) {
        next('/')
    } else if (to.path === '/' && token) {
        next('/dashboard')
    } else {
        next()
    }
})

export default router