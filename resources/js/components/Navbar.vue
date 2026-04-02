<template>
    <nav class="bg-red-600 shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="text-white text-xl font-bold">
                    Pharmacovigilance System
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-white">Welcome, {{ userName }}</span>
                    <button 
                        @click="logout"
                        class="bg-white text-red-600 hover:bg-gray-100 font-bold py-2 px-4 rounded-lg"
                    >
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import api from '../services/api'

export default {
    name: 'Navbar',
    computed: {
        userName() {
            const user = localStorage.getItem('user')
            if (user) {
                return JSON.parse(user).name
            }
            return 'User'
        }
    },
    methods: {
        async logout() {
            try {
                await api.post('/logout')
            } catch (error) {
                console.error('Logout error:', error)
            } finally {
                localStorage.removeItem('token')
                localStorage.removeItem('user')
                this.$router.push('/')
            }
        }
    }
}
</script>