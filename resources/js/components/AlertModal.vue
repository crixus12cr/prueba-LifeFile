<template>
    <Teleport to="body">
        <div 
            v-if="visible"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black/50 backdrop-blur-sm transition-all duration-300"
            @click.self="closeModal"
        >
            <div 
                class="bg-white rounded-xl shadow-2xl w-96 p-6 transform transition-all duration-300 animate-fade-in-up"
            >
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Confirm Alert</h3>
                    </div>
                    <button 
                        @click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="mb-6">
                    <p class="text-gray-600 mb-3">
                        Are you sure you want to send an alert to:
                    </p>
                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                        <p class="font-semibold text-gray-800">{{ order?.customer?.name }}</p>
                        <p class="text-sm text-gray-500">{{ order?.customer?.email }}</p>
                    </div>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 rounded">
                        <p class="text-sm text-yellow-800">
                            <strong>Warning:</strong> This action cannot be undone. An email will be sent immediately.
                        </p>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button 
                        @click="closeModal"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors duration-200"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="confirmModal"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Send Alert
                    </button>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script>
export default {
    name: 'AlertModal',
    props: {
        order: {
            type: Object,
            required: true
        },
        visible: {
            type: Boolean,
            default: false
        }
    },
    emits: ['close', 'confirm'],
    methods: {
        closeModal() {
            this.$emit('close')
        },
        confirmModal() {
            this.$emit('confirm', this.order)
        }
    }
}
</script>

<style scoped>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fade-in-up 0.2s ease-out;
}
</style>