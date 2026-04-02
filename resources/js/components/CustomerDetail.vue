<template>
    <div>
        <Navbar />
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Customer Details</h1>
                    <button 
                        @click="$router.back()"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg"
                    >
                        Back
                    </button>
                </div>
                
                <div v-if="customer" class="space-y-4">
                    <div class="border-b pb-4">
                        <p><strong>Name:</strong> {{ customer.name }}</p>
                        <p><strong>Email:</strong> {{ customer.email }}</p>
                        <p><strong>Phone:</strong> {{ customer.phone || 'N/A' }}</p>
                        <p><strong>Member Since:</strong> {{ $formatDate(customer.created_at) }}</p>
                    </div>
                </div>
                
                <div v-else class="text-center py-8 text-gray-500">
                    Loading customer details...
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from './Navbar.vue'
import api from '../services/api'

export default {
    name: 'CustomerDetail',
    components: {
        Navbar
    },
    data() {
        return {
            customer: null
        }
    },
    methods: {
        async loadCustomer() {
            try {
                const response = await api.get(`/customers/${this.$route.params.id}`)
                this.customer = response.data.data
            } catch (error) {
                console.error('Error loading customer:', error)
                if (error.response?.status === 401) {
                    this.$router.push('/')
                }
            }
        }
    },
    mounted() {
        this.loadCustomer()
    }
}
</script>