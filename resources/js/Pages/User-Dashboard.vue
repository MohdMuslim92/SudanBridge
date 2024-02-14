<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const shipments = ref([]);
const facilitiesResponse = ref([]);

const form = useForm({
    itemName: '',
    itemDescription: '',
    senderName: '',
    senderEmail: '',
    senderPhone: '',
    senderState: '',
    senderCity: '',
    senderStreet: '',
    senderAddressDetails: '',
    recipientName: '',
    recipientEmail: '',
    recipientPhone: '',
    recipientState: '',
    recipientCity: '',
    recipientStreet: '',
    recipientAddressDetails: '',
    recipientNearFacility: ''
});

// Fetch user data and shipments on component mount
onMounted(async () => {
    try {
        // Fetch shipments associated with the user
        const response = await axios.get('/api/shipments');
        shipments.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    }

    try {
        // Fetch facilities
        const facilities = await axios.get('/api/facilities');
        facilitiesResponse.value = facilities.data;
        console.log(facilitiesResponse.value);
    } catch (error) {
        console.error('Error fetching data:', error);
    }

});

// Function to add a new shipment
const addShipment = async () => {
    try {
        await form.post('/api/shipments', {
            onSuccess: async () => { // Mark the onSuccess callback as async
                form.reset();
                // Fetch updated shipment list
                try {
                    const response = await axios.get('/api/shipments'); // Now you can use await here
                    shipments.value = response.data;
                } catch (error) {
                    console.error('Error fetching updated shipment list:', error);
                }
            },
            onError: (errors) => {
                console.error('Error adding shipment:', errors);
            }
        });
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
                        <label for="itemName" class="block text-gray-700 text-sm font-bold mb-2">Item Name</label>
                        <input type="text" id="itemName" v-model="form.itemName" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="itemDescription" class="block text-gray-700 text-sm font-bold mb-2">Item Description</label>
                        <textarea id="itemDescription" v-model="form.itemDescription" class="form-textarea w-full"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="senderName" class="block text-gray-700 text-sm font-bold mb-2">Sender Name</label>
                        <input type="text" id="senderName" v-model="form.senderName" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="senderEmail" class="block text-gray-700 text-sm font-bold mb-2">Sender Email</label>
                        <input type="email" id="senderEmail" v-model="form.senderEmail" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="senderPhone" class="block text-gray-700 text-sm font-bold mb-2">Sender Phone</label>
                        <input type="text" id="senderPhone" v-model="form.senderPhone" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="senderState" class="block text-gray-700 text-sm font-bold mb-2">Sender State</label>
                        <!-- Dropdown menu for sender state -->
                        <select id="senderState" v-model="form.senderState" class="form-select w-full">
                            <option value="state1">State 1</option>
                            <option value="state2">State 2</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="senderCity" class="block text-gray-700 text-sm font-bold mb-2">Sender City</label>
                        <!-- Dropdown menu for sender city -->
                        <select id="senderCity" v-model="form.senderCity" class="form-select w-full">
                            <option value="city1">City 1</option>
                            <option value="city2">City 2</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="senderStreet" class="block text-gray-700 text-sm font-bold mb-2">Sender Street</label>
                        <input type="text" id="senderStreet" v-model="form.senderStreet" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="senderAddressDetails" class="block text-gray-700 text-sm font-bold mb-2">Sender Address Details</label>
                        <textarea id="senderAddressDetails" v-model="form.senderAddressDetails" class="form-textarea w-full"></textarea>
                    </div>

                    <!-- Recipient Information -->
                    <div class="mb-4">
                        <label for="recipientName" class="block text-gray-700 text-sm font-bold mb-2">Recipient Name</label>
                        <input type="text" id="recipientName" v-model="form.recipientName" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="recipientEmail" class="block text-gray-700 text-sm font-bold mb-2">Recipient Email</label>
                        <input type="email" id="recipientEmail" v-model="form.recipientEmail" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="recipientPhone" class="block text-gray-700 text-sm font-bold mb-2">Recipient Phone</label>
                        <input type="text" id="recipientPhone" v-model="form.recipientPhone" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="recipientState" class="block text-gray-700 text-sm font-bold mb-2">Recipient State</label>
                        <!-- Dropdown menu for recipient state -->
                        <select id="recipientState" v-model="form.recipientState" class="form-select w-full">
                            <option value="state1">State 1</option>
                            <option value="state2">State 2</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="recipientCity" class="block text-gray-700 text-sm font-bold mb-2">Recipient City</label>
                        <!-- Dropdown menu for recipient city -->
                        <select id="recipientCity" v-model="form.recipientCity" class="form-select w-full">
                            <option value="city1">City 1</option>
                            <option value="city2">City 2</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="recipientStreet" class="block text-gray-700 text-sm font-bold mb-2">Recipient Street</label>
                        <input type="text" id="recipientStreet" v-model="form.recipientStreet" class="form-input w-full">
                    </div>
                    <div class="mb-4">
                        <label for="recipientAddressDetails" class="block text-gray-700 text-sm font-bold mb-2">Recipient Address Details</label>
                        <textarea id="recipientAddressDetails" v-model="form.recipientAddressDetails" class="form-textarea w-full"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="recipientNearFacility" class="block text-gray-700 text-sm font-bold mb-2">Recipient Near Facility</label>
                        <!-- Dropdown menu for recipient near facility -->
                        <select id="recipientNearFacility" v-model="form.recipientNearFacility" class="form-select w-full">
                            <!-- Loop through the facilities and generate options dynamically -->
                            <option v-for="facility in facilitiesResponse" :key="facility.id" :value="facility.id">
                                {{ facility.name }} - {{ facility.location }}
                            </option>
                        </select>
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
