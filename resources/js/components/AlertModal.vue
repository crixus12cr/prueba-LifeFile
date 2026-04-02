<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl w-96 p-6">
            <h3 class="text-xl font-bold mb-4 text-gray-800">Confirm Alert</h3>
            <p class="text-gray-600 mb-4">
                Are you sure you want to send an alert to <strong>{{ order?.customer?.name }}</strong>?
            </p>
            <p class="text-gray-600 mb-6">
                An email will be sent to: <strong>{{ order?.customer?.email }}</strong>
            </p>
            <div class="flex justify-end gap-3">
                <button 
                    @click="$emit('close')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg"
                >
                    Cancel
                </button>
                <button 
                    @click="$emit('confirm', order)"
                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg"
                >
                    Send Alert
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AlertModal',
    props: {
        order: {
            type: Object,
            required: true
        }
    },
    emits: ['close', 'confirm']
}
</script>