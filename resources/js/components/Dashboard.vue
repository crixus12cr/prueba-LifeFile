<template>
    <div>
        <Navbar />
        <div class="container mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold mb-6 text-gray-800">Medication Search</h1>
                
                <!-- Search Form -->
                <div class="mb-8 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Lot Number *</label>
                            <input 
                                type="text" 
                                v-model="filters.lot_number"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                                placeholder="951357"
                            >
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Start Date</label>
                            <input 
                                type="date" 
                                v-model="filters.start_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                            >
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">End Date</label>
                            <input 
                                type="date" 
                                v-model="filters.end_date"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                            >
                        </div>
                    </div>
                    <div class="mt-4">
                        <button 
                            @click="searchOrders"
                            :disabled="loading"
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg disabled:opacity-50"
                        >
                            {{ loading ? 'Searching...' : 'Search Orders' }}
                        </button>
                    </div>
                </div>
                
                <!-- Orders Table -->
                <div v-if="orders.length > 0">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Orders Found ({{ orders.length }})</h2>
                        <div class="flex gap-2">
                            <button 
                                @click="exportToExcel"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg text-sm"
                            >
                                Export Excel
                            </button>
                            <button 
                                @click="exportToPDF"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg text-sm"
                            >
                                Export PDF
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-3 text-left">Order ID</th>
                                    <th class="border p-3 text-left">Customer Name</th>
                                    <th class="border p-3 text-left">Email</th>
                                    <th class="border p-3 text-left">Phone</th>
                                    <th class="border p-3 text-left">Purchase Date</th>
                                    <th class="border p-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
                                    <td class="border p-3">#{{ order.id }}</td>
                                    <td class="border p-3">{{ order.customer.name }}</td>
                                    <td class="border p-3">{{ order.customer.email }}</td>
                                    <td class="border p-3">{{ order.customer.phone || 'N/A' }}</td>
                                    <td class="border p-3">{{ order.purchase_date }}</td>
                                    <td class="border p-3 text-center">
                                        <div class="flex justify-center gap-2">
                                            <button 
                                                @click="viewOrder(order.id)"
                                                class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm"
                                            >
                                                View Order
                                            </button>
                                            <button 
                                                @click="viewCustomer(order.customer_id)"
                                                class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm"
                                            >
                                                View Buyer
                                            </button>
                                            <button 
                                                @click="openAlertModal(order)"
                                                class="bg-red-500 hover:bg-red-700 text-white px-3 py-1 rounded text-sm"
                                            >
                                                Alert Buyer
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div v-else-if="searched && orders.length === 0" class="text-center py-8 text-gray-500">
                    No orders found for the specified lot number.
                </div>
            </div>
        </div>
        
        <!-- Alert Modal -->
        <AlertModal 
            v-if="showModal"
            :order="selectedOrder"
            @close="showModal = false"
            @confirm="sendAlert"
        />
    </div>
</template>

<script>
import Swal from 'sweetalert2'
import Navbar from './Navbar.vue'
import AlertModal from './AlertModal.vue'
import api from '../services/api'

export default {
    name: 'Dashboard',
    components: {
        Navbar,
        AlertModal
    },
    data() {
        return {
            filters: {
                lot_number: '',
                start_date: '',
                end_date: ''
            },
            orders: [],
            loading: false,
            searched: false,
            showModal: false,
            selectedOrder: null
        }
    },
    methods: {
        async searchOrders() {
            if (!this.filters.lot_number) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: 'Lot number is required',
                    confirmButtonColor: '#dc2626'
                })
                return
            }
            
            this.loading = true
            this.searched = true
            
            try {
                const params = new URLSearchParams()
                params.append('lot_number', this.filters.lot_number)
                if (this.filters.start_date) params.append('start_date', this.filters.start_date)
                if (this.filters.end_date) params.append('end_date', this.filters.end_date)
                
                const response = await api.get(`/orders?${params.toString()}`)
                this.orders = response.data.data
                
                if (this.orders.length === 0) {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Results',
                        text: 'No orders found for lot number ' + this.filters.lot_number,
                        confirmButtonColor: '#dc2626'
                    })
                }
            } catch (error) {
                console.error('Search error:', error)
                this.orders = []
                Swal.fire({
                    icon: 'error',
                    title: 'Search Failed',
                    text: error.response?.data?.message || 'An error occurred while searching',
                    confirmButtonColor: '#dc2626'
                })
                if (error.response?.status === 401) {
                    this.$router.push('/')
                }
            } finally {
                this.loading = false
            }
        },
        
        viewOrder(id) {
            this.$router.push(`/orders/${id}`)
        },
        
        viewCustomer(id) {
            this.$router.push(`/customers/${id}`)
        },
        
        openAlertModal(order) {
            this.selectedOrder = order
            this.showModal = true
        },
        
        async sendAlert(orderData) {
            try {
                await api.post('/alerts/send', {
                    customer_id: orderData.customer.id,
                    order_id: orderData.id,
                    medication_name: 'Medication with lot ' + this.filters.lot_number,
                    lot_number: this.filters.lot_number
                })
                
                Swal.fire({
                    icon: 'success',
                    title: 'Alert Sent',
                    text: `Alert sent successfully to ${orderData.customer.email}`,
                    confirmButtonColor: '#dc2626'
                })
                this.showModal = false
            } catch (error) {
                console.error('Alert error:', error)
                Swal.fire({
                    icon: 'error',
                    title: 'Alert Failed',
                    text: error.response?.data?.message || 'An error occurred while sending the alert',
                    confirmButtonColor: '#dc2626'
                })
            }
        },
        
        async exportToExcel() {
            try {
                const response = await api.get(`/orders/export/excel`, {
                    params: {
                        lot_number: this.filters.lot_number,
                        start_date: this.filters.start_date,
                        end_date: this.filters.end_date
                    },
                    responseType: 'blob'
                })
                
                const url = window.URL.createObjectURL(new Blob([response.data]))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', `orders_lot_${this.filters.lot_number}.xlsx`)
                document.body.appendChild(link)
                link.click()
                document.body.removeChild(link)
                
                Swal.fire({
                    icon: 'success',
                    title: 'Export Complete',
                    text: 'Excel file has been downloaded',
                    confirmButtonColor: '#dc2626'
                })
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Export Failed',
                    text: error.response?.data?.message || 'An error occurred while exporting',
                    confirmButtonColor: '#dc2626'
                })
            }
        },
        
        async exportToPDF() {
            try {
                const response = await api.get(`/orders/export/pdf`, {
                    params: {
                        lot_number: this.filters.lot_number,
                        start_date: this.filters.start_date,
                        end_date: this.filters.end_date
                    },
                    responseType: 'blob'
                })
                
                const url = window.URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }))
                const link = document.createElement('a')
                link.href = url
                link.setAttribute('download', `orders_lot_${this.filters.lot_number}.pdf`)
                document.body.appendChild(link)
                link.click()
                document.body.removeChild(link)
                
                Swal.fire({
                    icon: 'success',
                    title: 'Export Complete',
                    text: 'PDF file has been downloaded',
                    confirmButtonColor: '#dc2626'
                })
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Export Failed',
                    text: error.response?.data?.message || 'An error occurred while exporting',
                    confirmButtonColor: '#dc2626'
                })
            }
        }
    }
}
</script>