<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const shipments = ref([]);
const newShipment = ref({ name: '', description: '' })

// Fetch user data and shipments on component mount
onMounted(async () => {
    try {
        // Fetch shipments associated with the user
        const response = await axios.get('/api/shipments');
        shipments.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});

// Function to add a new shipment
const addShipment = async () => {
    try {
        // Send a POST request to create a new shipment
        await axios.post('/api/shipments', newShipment.value);

        // Fetch updated shipment list
        const response = await axios.get('/api/shipments');
        shipments.value = response.data;

        // Clear the form fields
        newShipment.value.name = '';
        newShipment.value.description = '';
    } catch (error) {
        console.error('Error adding shipment:', error);
    }
};

// Function to update the status of a shipment
const updateShipmentStatus = async (shipmentId) => {
    // Logic to update the status of a shipment
};

// Function to delete a shipment
const deleteShipment = async (shipmentId) => {
    // Logic to delete a shipment
};
</script>

<template>
    <Head title="User Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Dashboard</h2>
        </template>

        <br>
        <h1 class="welcome-message">Welcome, {{ $page.props.auth.user.name }}!</h1>
        <div class="py-12 flex justify-center">
            <div class="max-w-3xl w-full bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Form to add a new shipment -->
                <form @submit.prevent="addShipment">
                    <div class="mb-4">
                        <label for="shipmentName" class="block text-gray-700 text-sm font-bold mb-2">Shipment Name</label>
                        <input type="text" id="shipmentName" v-model="newShipment.name" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="shipmentDescription" class="block text-gray-700 text-sm font-bold mb-2">Shipment Description</label>
                        <textarea id="shipmentDescription" v-model="newShipment.description" class="form-textarea w-full"></textarea>
                    </div>
                    <div class="mb-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Shipment</button>
                    </div>
                </form>

                <!-- Display Shipments -->
                <h3>My Shipments</h3>
                <ul>
                    <li v-for="shipment in shipments" :key="shipment.id">
                        {{ shipment.name }} - {{ shipment.status }}
                        <!-- Buttons to update status and delete shipment -->
                        <button @click="updateShipmentStatus(shipment.id)">Update Status</button>
                        <button @click="deleteShipment(shipment.id)">Delete</button>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.welcome-message {
    padding-left: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
}
</style>
