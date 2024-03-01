<script setup>
import {onMounted, ref} from "vue";
import axios from "axios";
import { defineProps } from 'vue';


const searchToken = ref(''); // Ref to store the search token entered by the user
const shipments = ref('');
const shipmentData = ref('');
const statuses = ref('');

const props = defineProps({
    showNotification: Function,
    closeModal: Function
});

// Function to search shipments by token
const searchByToken = async () => {
    // Check if the search token is not empty
    if (searchToken.value.trim() !== '') {
        try {
            // Fetch shipment details using the search token
            const shipmentDetails = await fetchShipmentDetails(searchToken.value.trim());
            if (shipmentDetails.error && shipmentDetails.error === 'Shipment not found') {
                alert('Shipment not found');
            } else {
                // Display the modal with shipment details
                showModal(shipmentDetails);
            }
        } catch (error) {
            alert('An error occurred while fetching shipment details. Please try again.');
        }
    } else {
        alert('Please enter a token to search.'); // Display an alert if the search token is empty
    }
};

// Function to fetch shipment details from the backend
const fetchShipmentDetails = async (token) => {
    const response = await fetch(`/api/shipments/${token}`);
    if (!response.ok) {
        throw new Error('Shipment not found');
    }
    return await response.json(); // Return shipment details if successful
};

// Function to display the modal with shipment details
const showModal = (shipmentDetails) => {
    // Set the shipment details to be displayed in the modal
    shipmentData.value = shipmentDetails;
    $('.shipmentByTokenModal').modal('show'); // Manually trigger the Bootstrap modal
};

onMounted(async () => {
    try {
        // Fetch statuses
        const statusResponse = await axios.get(`/api/statuses`);
        statuses.value = statusResponse.data;

    } catch (error) {
        console.error('Error fetching data:', error);
    }
});
// Function to update status in shipment
const saveStatus = async (trackingToken, statusId) => {
    try {
        // Make a request to update the shipment details
        if (statusId) {
            const saveStatusResponse = await axios.post(`/api/shipments/${trackingToken}`, { status_id: statusId });
            // Show notification
            props.showNotification("Shipment status updated"); // Call showNotification using props
            // Refetch shipments after update
            const shipmentResponse = await axios.get('/api/shipments');
            shipments.value = shipmentResponse.data;
            // Close the Modal
            props.closeModal(); // Call closeModal using props
        } else {
            console.error('No shipment selected.');
        }
    } catch (error) {
        console.error('Error saving changes:', error);
    }
};
</script>

<template>
    <!-- Search input field and button -->
    <div class="ml-auto search-box">
        <input type="text" v-model="searchToken" placeholder="Enter token" class="border border-gray-300 p-1 rounded-lg mr-2">
        <button @click="searchByToken" class="search-button bg-blue-500 text-white px-4 py-1 rounded-lg">Search</button>
    </div>
    <!-- Modal component -->
    <div class="shipmentByTokenModal modal fade" id="shipmentByTokenModal" tabindex="-1" role="dialog" aria-labelledby="shipmentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog max-w-lg mx-auto" role="document">
            <div class="modal-content bg-white rounded-md shadow-lg">
                <div class="modal-header bg-blue-500 rounded-t-md">
                    <h5 class="modal-title text-lg font-bold text-white" id="shipmentDetailsModalLabel">Shipment Status</h5>
                    <button type="button" class="close" aria-label="Close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4" v-if="shipmentData">
                    <!-- Shipment Details -->
                    <div class="bg-purple-300 px-4 py-4 rounded-md mb-0.5">
                        <p class="text-lg font-bold text-center text-purple-800">Shipment : {{ shipmentData.tracking_token }}</p>
                        <span><strong>Item Name:</strong> {{ shipmentData.item?.name }}</span><br>
                        <span><strong>Sender Name:</strong> {{ shipmentData.sender?.name }}</span><br>
                        <span><strong>Recipient Name:</strong> {{ shipmentData.recipient?.name }}</span><br>
                        <span><strong>Facility:</strong> {{ shipmentData.recipient?.facility ? shipmentData.recipient?.facility.location : 'N/A' }}</span><br>
                        <span><strong>Tracking Token:</strong> {{ shipmentData.tracking_token }}</span>
                        <!-- Dropdown menu for status -->
                        <div class="mt-4">
                            <label for="status" class="font-bold">Status:</label>
                            <select v-model="shipmentData.status_id" id="status" class="form-control">
                                <option v-for="status in statuses" :value="status.id">{{ status.name }}</option>
                            </select>
                        </div>
                    </div>
                    <!-- Modal Footer with Save button -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="saveStatus(shipmentData.tracking_token, shipmentData.status_id)">Save</button>
                        <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
