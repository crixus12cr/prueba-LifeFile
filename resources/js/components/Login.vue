<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Pharmacovigilance Login</h2>
            
            <form @submit.prevent="handleLogin">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input 
                        type="email" 
                        v-model="form.email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        required
                    >
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input 
                        type="password" 
                        v-model="form.password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        required
                    >
                </div>
                
                <div v-if="error" class="mb-4 text-red-500 text-sm text-center">
                    {{ error }}
                </div>
                
                <button 
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg disabled:opacity-50"
                >
                    {{ loading ? 'Logging in...' : 'Login' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script>
import api from '../services/api'

export default {
    name: 'Login',
    data() {
        return {
            form: {
                email: '',
                password: ''
            },
            loading: false,
            error: null
        }
    },
    methods: {
        async handleLogin() {
            this.loading = true
            this.error = null
            
            try {
                const response = await api.post('/login', this.form)
                const token = response.data.data.token
                localStorage.setItem('token', token)
                this.$router.push('/dashboard')
            } catch (error) {
                this.error = error.response?.data?.message || 'Invalid credentials'
            } finally {
                this.loading = false
            }
        }
    }
}
</script>