<script setup>
import { ref, defineProps } from "vue";
import axios from "axios";
import { useForm } from '@inertiajs/vue3';
// Import the useFacilities function
import useFacilities from '../facilities.js';

// Destructure facilitiesResponse from the useFacilities function
const { facilitiesResponse } = useFacilities();
const props = defineProps({
    showNotification: Function
});

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

const showItemDetails = ref(false);
const showSenderDetails = ref(false);
const showRecipientDetails = ref(false);
// Add data properties to control the visibility of add shipment sections
const itemDetails = ref(false);
const senderDetails = ref(false);
const recipientDetails = ref(false);


// Function to toggle visibility of item details in add shipment section
const toggleItem = () => {
    itemDetails.value = !itemDetails.value;
    senderDetails.value = false;
    recipientDetails.value = false;
};

// Function to toggle visibility of sender details in add shipment section
const toggleSender = () => {
    senderDetails.value = !senderDetails.value;
    itemDetails.value = false;
    recipientDetails.value = false;
};

// Function to toggle visibility of recipient details in add shipment section
const toggleRecipient = () => {
    recipientDetails.value = !recipientDetails.value;
    itemDetails.value = false;
    senderDetails.value = false;
};

const addShipment = async () => {
    try {
        const formData = form.data(); // Retrieve form data as a plain JavaScript object
        // Make a request to add the shipment using form data
        const response = await axios.post('/api/shipments', formData);
        // Show notification
        props.showNotification("Shipment added successfully"); // Call showNotification using props
    } catch (error) {
        alert('Error adding shipment');
    }
};
</script>

<template>
    <div class="py-12 flex justify-center">
        <div class="max-w-3xl w-full bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <!-- Form to add a new shipment -->
            <h3>Add a Shipment</h3>
            <form @submit.prevent="addShipment">
                <div class="bg-purple-300 rounded-md">
                    <button @click="toggleItem" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
                        <span>Item Details</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div v-show="itemDetails" class="border border-purple-500 rounded-md p-4">
                        <label for="itemName" class="block text-gray-700 text-sm font-bold mb-2">Item Name</label>
                        <input type="text" id="itemName" v-model="form.itemName" class="form-input w-full">
                        <label for="itemDescription" class="block text-gray-700 text-sm font-bold mb-2">Item Description</label>
                        <textarea id="itemDescription" v-model="form.itemDescription" class="form-textarea w-full"></textarea>
                    </div>
                </div>
                <div class="bg-purple-300 rounded-md">
                    <button @click="toggleSender" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
                        <span>Sender Details</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div v-show="senderDetails" class="border border-purple-500 rounded-md p-4">
                        <label for="senderName" class="block text-gray-700 text-sm font-bold mb-2">Sender Name</label>
                        <input type="text" id="senderName" v-model="form.senderName" class="form-input w-full">
                        <label for="senderEmail" class="block text-gray-700 text-sm font-bold mb-2">Sender Email</label>
                        <input type="email" id="senderEmail" v-model="form.senderEmail" class="form-input w-full">
                        <label for="senderPhone" class="block text-gray-700 text-sm font-bold mb-2">Sender Phone</label>
                        <input type="text" id="senderPhone" v-model="form.senderPhone" class="form-input w-full">
                        <label for="senderState" class="block text-gray-700 text-sm font-bold mb-2">Sender State</label>
                        <!-- Dropdown menu for sender state -->
                        <select id="senderState" v-model="form.senderState" class="form-select w-full">
                            <option value="state1">State 1</option>
                            <option value="state2">State 2</option>
                        </select>
                        <label for="senderCity" class="block text-gray-700 text-sm font-bold mb-2">Sender City</label>
                        <!-- Dropdown menu for sender city -->
                        <select id="senderCity" v-model="form.senderCity" class="form-select w-full">
                            <option value="city1">City 1</option>
                            <option value="city2">City 2</option>
                        </select>
                        <label for="senderStreet" class="block text-gray-700 text-sm font-bold mb-2">Sender Street</label>
                        <input type="text" id="senderStreet" v-model="form.senderStreet" class="form-input w-full">
                        <label for="senderAddressDetails" class="block text-gray-700 text-sm font-bold mb-2">Sender Address Details</label>
                        <textarea id="senderAddressDetails" v-model="form.senderAddressDetails" class="form-textarea w-full"></textarea>
                    </div>
                </div>

                <!-- Recipient Information -->
                <div class="bg-purple-300 rounded-md">
                    <button @click="toggleRecipient" type="button" class="w-full bg-purple-500 text-white
                                    text-left p-2 rounded-md mb-1 flex justify-between items-center">
                        <span>Recipient Details</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transform rotate-0 transition-transform" :class="{ 'rotate-180': showItemDetails }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 12a1 1 0 0 1-1-1V7a1 1 0 0 1 2 0v4a1 1 0 0 1-1 1zM8 9a1 1 0 0 1 0-2h4a1 1 0 0 1 0 2H8z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div v-show="recipientDetails" class="border border-purple-500 rounded-md p-4">
                        <label for="recipientName" class="block text-gray-700 text-sm font-bold mb-2">Recipient Name</label>
                        <input type="text" id="recipientName" v-model="form.recipientName" class="form-input w-full">
                        <label for="recipientEmail" class="block text-gray-700 text-sm font-bold mb-2">Recipient Email</label>
                        <input type="email" id="recipientEmail" v-model="form.recipientEmail" class="form-input w-full">
                        <label for="recipientPhone" class="block text-gray-700 text-sm font-bold mb-2">Recipient Phone</label>
                        <input type="text" id="recipientPhone" v-model="form.recipientPhone" class="form-input w-full">
                        <label for="recipientState" class="block text-gray-700 text-sm font-bold mb-2">Recipient State</label>
                        <!-- Dropdown menu for recipient state -->
                        <select id="recipientState" v-model="form.recipientState" class="form-select w-full">
                            <option value="state1">State 1</option>
                            <option value="state2">State 2</option>
                        </select>
                        <label for="recipientCity" class="block text-gray-700 text-sm font-bold mb-2">Recipient City</label>
                        <!-- Dropdown menu for recipient city -->
                        <select id="recipientCity" v-model="form.recipientCity" class="form-select w-full">
                            <option value="city1">City 1</option>
                            <option value="city2">City 2</option>
                        </select>
                        <label for="recipientStreet" class="block text-gray-700 text-sm font-bold mb-2">Recipient Street</label>
                        <input type="text" id="recipientStreet" v-model="form.recipientStreet" class="form-input w-full">
                        <label for="recipientAddressDetails" class="block text-gray-700 text-sm font-bold mb-2">Recipient Address Details</label>
                        <textarea id="recipientAddressDetails" v-model="form.recipientAddressDetails" class="form-textarea w-full"></textarea>
                        <label for="recipientNearFacility" class="block text-gray-700 text-sm font-bold mb-2">Recipient Near Facility</label>
                        <!-- Dropdown menu for recipient near facility -->
                        <select id="recipientNearFacility" v-model="form.recipientNearFacility" class="form-select w-full">
                            <!-- Loop through the facilities and generate options dynamically -->
                            <option v-for="facility in facilitiesResponse" :key="facility.id" :value="facility.id">
                                {{ facility.name }} - {{ facility.location }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Shipment</button>
                </div>
            </form>
        </div>
    </div>
</template>
