<template>
    <div>
        <Navbar />
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Order Details</h1>
                    <button 
                        @click="$router.back()"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg"
                    >
                        Back
                    </button>
                </div>
                
                <div v-if="order" class="space-y-6">
                    <div class="border-b pb-4">
                        <h2 class="text-xl font-semibold mb-3">Order Information</h2>
                        <p><strong>Order ID:</strong> #{{ order.id }}</p>
                        <p><strong>Purchase Date:</strong> {{ order.purchase_date }}</p>
                    </div>
                    
                    <div class="border-b pb-4">
                        <h2 class="text-xl font-semibold mb-3">Customer Information</h2>
                        <p><strong>Name:</strong> {{ order.customer?.name }}</p>
                        <p><strong>Email:</strong> {{ order.customer?.email }}</p>
                        <p><strong>Phone:</strong> {{ order.customer?.phone || 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <h2 class="text-xl font-semibold mb-3">Medications</h2>
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-3 text-left">Medication</th>
                                    <th class="border p-3 text-left">Lot Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in order.order_items" :key="item.id">
                                    <td class="border p-3">{{ item.medication?.name }}</td>
                                    <td class="border p-3">{{ item.medication?.lot_number }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div v-else class="text-center py-8 text-gray-500">
                    Loading order details...
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Navbar from './Navbar.vue'
import api from '../services/api'

export default {
    name: 'OrderDetail',
    components: {
        Navbar
    },
    data() {
        return {
            order: null
        }
    },
    methods: {
        async loadOrder() {
            try {
                const response = await api.get(`/orders/${this.$route.params.id}`)
                this.order = response.data.data
            } catch (error) {
                console.error('Error loading order:', error)
                if (error.response?.status === 401) {
                    this.$router.push('/')
                }
            }
        }
    },
    mounted() {
        this.loadOrder()
    }
}
</script>